<?php
namespace app\install\behavior;

class Test 
{
    public function appInit($params)
    {
          echo "安装1";
    }
    
    public function appEnd($params)
    {
          echo "安装2";
    }    
}
?>