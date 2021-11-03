<?php
/*
*   08.11.2019
*   RenderFromDatabaseData
*/

namespace App\MenuBuilder;
use App\MenuBuilder\MenuBuilder;
use App\Models\Setting;

class RenderFromDatabaseData{

    private $mb; // MenuBuilder
    private $data;
    private $setting_config;

    public function __construct(){
        $this->mb = new MenuBuilder();
    }

    private function addTitle($data){
        $this->mb->addTitle($data['id'], $data['name'], false, 'coreui', $data['sequence']);
    }

    private function addLink($data){
        if($data['parent_id'] === NULL){
            $this->mb->addLink($data['id'], $data['name'], $data['href'], $data['icon'], 'coreui', $data['sequence']);
        }
    }

    private function dropdownLoop($id){
        for($i = 0; $i<count($this->data); $i++){
            if($this->data[$i]['parent_id'] == $id){
                if($this->data[$i]['slug'] === 'dropdown'){
                    $this->addDropdown($this->data[$i]);
                }elseif($this->data[$i]['slug'] === 'link'){
                    $this->mb->addLink($this->data[$i]['id'], $this->data[$i]['name'], $this->data[$i]['href'], $this->data[$i]['icon'], 'coreui', $this->data[$i]['sequence']);
                }else{
                    $this->addTitle($this->data[$i]);
                }
            }
        }
    }
    
    private function addDropdown($data){
        $this->mb->beginDropdown($data['id'], $data['name'], $data['icon'], 'coreui', $data['sequence']);
        $this->dropdownLoop($data['id']);
        $this->mb->endDropdown();
    }

    private function mainLoop(){
        for($i = 0; $i<count($this->data); $i++){
            if($this->data[$i]['name'] == "Realtime") {

            } else {

            }
            switch ($this->data[$i]['name']) {
                case 'Realtime':
                    if ($this->setting_config->realtime->liveUserWidget == "on") {
                        $this->generate_links($this->data, $i);
                    }
                    break;
                
                default:
                    $this->generate_links($this->data, $i);
                    break;
            }
        }
    }

    public function generate_links($data, $index) {
        switch($data[$index]['slug']){
            case 'title':
                $this->addTitle($data[$index]);
            break;
            case 'link':
                $this->addLink($data[$index]);
            break;
            case 'dropdown':
                if($data[$index]['parent_id'] == null){
                    $this->addDropdown($data[$index]);
                }
            break;
        }
    }

    /***
     * $data - array
     * return - array
     */
    public function render($data){
        $settings = Setting::where('user_id', auth()->user()->id)->first();
        $config = json_decode($settings->config);
        if(!empty($data)){
            $this->setting_config = $config;
            $this->data = $data;
            $this->mainLoop();
        }
        return $this->mb->getResult();
    }

}