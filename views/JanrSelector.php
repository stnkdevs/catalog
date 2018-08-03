<?php

namespace views;

use models\Janr;

class JanrSelector{
    
    
    
    private $count=0;
    
    private function is_checked($janr){
    foreach($this->selected as $j)
        if ($j->id==$janr->id)
                return true;
    return false;
}
    
    public function show(){
        
        
        
        $janrs=Janr::getJanrs();
        $this->count++;
        
        
        ?>
<div class="janrselector"><!-- book_janrcodes[] PHP variable by JS -->
    <?php 
    

    foreach($janrs as $janr){ $checked = $this->is_checked($janr)?'checked':'';?>
    <input type="checkbox" data_id="<?=$janr->id?>" onchange="itemChanged()" <?=$checked?>><?=$janr->title?><br>
    <?php } ?>
    <input type="hidden" name="janrcodes" value="[]">
</div>

<style>
    .janrselector{
        border: 1px solid;
    }
</style>

<script type="text/javascript">
    
    $(document).on('submit', 'form', function(){
            itemChanged();   
    });
    
    function itemChanged(){
        var list=[];
        var d = document.getElementsByClassName('janrselector')[0];
        var inpx = d.getElementsByTagName('input');
        var resElem;
        for(let inp of inpx){
            if (inp.getAttribute('type')=='checkbox' && inp.checked){
                list.push(inp.getAttribute('data_id'));
            }
            else if (inp.getAttribute('type')=='hidden'){
                resElem=inp;
            }
        }
        resElem.value=JSON.stringify(list);
    }
</script>
<?php //endif ?>


<?php

    }
    
    public function getSelected($env)
    {
        if (!isset($env['janrcodes'])) return [];
        $codes=[];
        $janrcodes= json_decode($env['janrcodes']);
        if (is_array($janrcodes)){
            foreach($janrcodes as $janrcode){
                $code = filter_var($janrcode, FILTER_VALIDATE_INT, ['default'=>NULL]);
                if (is_null($code))
                    continue;
                $janr=new Janr();
                $janr->id=$code;
                $codes[]=$janr;
            }
        }
        return $codes;
    } 
    
    private $selected=[];
    
    public function setSelected($janrs){
        $this->selected=is_array($janrs)?$janrs:[];
    }
}
