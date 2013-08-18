<?php
class Controller
{
    private $do;

    private $modelName;
    private $modelResult;

    private $mvcGeneralConfig;
    private $modelConfig;

    public function __construct()
    {
        $this->loadLib();

        $this->do = null;
        $this->modelResult = array();

        $this->mvcGeneralConfig = parse_ini_file(CONFIG_BASE_PATH . 'mvc-general.conf', true);
        $this->modelConfig = parse_ini_file(CONFIG_BASE_PATH . 'mvc-model.conf', true);
    }

    private function setModelName()
    {
        $do = $_GET['do'];
        if($do !== false){
            $this->do = $do;
        }

        if(null === $this->do){
            $this->modelName = $this->modelConfig['default']['modelName'];
        }
        else{
            $this->modelName = $this->modelConfig[$this->do]['modelName'];
        }
    }

    private function loadLib()
    {
        require_once(LIB_BASE_PATH . 'db/Database.class.inc.php');
        require_once(LIB_BASE_PATH . 'db/SingleRecordOperator.class.inc.php');
        require_once(LIB_BASE_PATH . 'db/ListOperator.class.inc.php');
        require_once(LIB_BASE_PATH . 'db/PagingOperator.class.inc.php');
    }

    public function run()
    {
        $this->setModelName();

        try{
            $model = $this->loadModel();

            $model->setInput(array(
                'name' => '_do_',
                'value' => $this->do
            ));

            $model->loadLib();

            if($model->check()){
                $model->run();
            }

            $this->modelResult = $model->loadResult();
            $this->loadView();
        }
        catch(dbException $e){
            die($e->getMessage());
        }
    }

    private function loadModel()
    {
        $modelBasePath = $this->mvcGeneralConfig['mvc']['modelPath'];
        $modelLibPath = $modelBasePath . $this->modelName . '.class.inc.php';
        if(file_exists($modelLibPath)){
            require_once($modelLibPath);
        }

        if(true == class_exists($this->modelName)){
            return new $this->modelName();
        }

    }

    private function loadView()
    {
        $viewBasePath = $this->mvcGeneralConfig['mvc']['viewPath'];

        foreach($this->modelResult['data'] as $vName => $vValue){
            $$vName = $vValue;
        }

        require_once($viewBasePath . $this->modelResult['view'] . '.php');
    }
}
?>
