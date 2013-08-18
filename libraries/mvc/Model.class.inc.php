<?php
abstract class Model
{
    protected $input = array();
    protected $toView = null;

    protected $useTables = array();
    protected $useLibs = array();

    public function setInput($parame)
    {
        $this->input[$parame['name']] = $parame['value'];
    }

    protected function getInput($inName, $from = 'INPUT_GET', $filter = 'FILTER_SANITIZE_STRING')
    {
        if(isset($this->input[$inName]) == true){
            return true;
        }

        $inData = filter_input(const($from), $inName, const($filter));
        if(false === $inData || true == is_null($inData)){
            return false;
        }
        else{
            $this->input[$inName] = $inData;
            return true;
        }
    }

    public function loadLib(){
        if(false == empty($this->useTables)){
            foreach($this->useTables as $tableName){
                require_once(LIB_BASE_PATH . 'tables/' . $tableName . '.class.inc.php');
                if(true == file_exists(LIB_BASE_PATH . 'tables/' . $tableName . 'List.class.inc.php')){
                    require_once(LIB_BASE_PATH . 'tables/' . $tableName . 'List.class.inc.php');
                }
            }
        }

        if(false == empty($this->useLibs)){
            foreach($this->useLibs as $libInfo){
                if(true == file_exists(LIB_BASE_PATH . 'usrlib/' . $libInfo['name'] . '.' . $libInfo['type'] . '.inc.php')){
                    require_once(LIB_BASE_PATH . 'usrlib/' . $libInfo['name'] . '.' . $libInfo['type'] . '.inc.php');
                }
            }
        }
    }

    protected function getLinkUrl($doName = null, $urlArgs = array())
    {
        /*
        if(true == is_null($urlArgs)){
            $this->getInput('QUERY_STRING', 'YIV_SERVER');
            parse_str($this->input['QUERY_STRING'], $urlRawArgs);

            $urlArgs = array();
            foreach($urlRawArgs as $key => $value){
                if('do' == $key){
                    continue;
                }

                $urlArgs[$key] = $value;
            }
        }
        */
        
        if(null !== $doName){
            $modelConfig = parse_ini_file(CONFIG_BASE_PATH . 'mvc-general.conf', true);
            $allDoName = array_keys($modelConfig);
            if(false == in_array($doName, $allDoName)){
                return false;
            }

            $args = array_merge(array('do' => $doName), $urlArgs);
        }
        else if(null !== $this->input['_do_']){
            $args = array_merge(array('do' => $this->input['_do_']), $urlArgs);
        }
        else{
            $args = $urlArgs;
        }

        if(0 != count($args)){
            $linkUrl = 'index.php?' . http_build_query($args);
        }
        else{
            $linkUrl = 'index.php';
        }

        return $linkUrl;
    }

    public function check()
    {
        return true;
    }

    abstract public function run();

    protected function toView($view, $data)
    {
        $this->toView = array('view' => $view, 'data' => $data);
    }

    public function loadResult()
    {
        return $this->toView;
    }
}
?>
