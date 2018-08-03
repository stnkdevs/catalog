<?php

namespace views;

use models\Author;

class AuthorSelector {
    
private $selected=[];
    
public function show(){  ?>

<div class="authorselector">
    <input type="hidden" name="authors" value="[]">
<table>
    <tr>
        <td>Фамилия</td>
        <td><input type="text" name="author_surname" class="text_field"></td>
    </tr>
    <tr>
        <td></td>
        <td align="right"><input type="button" id="search" class="btn" value="Поиск"></td>
    </tr>
</table>
<ul class="sresult"></ul>
<table>
    <tr>
        <td>Имя</td>
        <td><input type="text" class="text_field" name="author_name"></td>
    </tr>
    <tr>
        <td>Отчество</td>
        <td><input type="text" class="text_field" name="author_fname"></td>
    </tr>
    <tr>
        <td></td>
        <td align="right"><input type="button" class="add_author btn" value="Добавить"></td>
    </tr>
</table>


<div class="selected_authors">
<span>Выбраны следующие авторы: </span>
<span class="zero_count">ничего не выбрано</span>
</div>
</div>

<style>
    .selected_authors{
        border: 3px dashed orange;
        padding: 10px;
        margin-top: 10px;   
    }
    .selected_authors .zero_count{
        color: red;
        display: block;
    }
    .selected_authors span{
        font-size: 18px;
        font-style: oblique;
    }
    .authorselector{
        width: 435px;
        border: 1px solid;
        padding: 10px;
    }
    .sresult{
        border: 1px dashed red;
        padding: 10px;
    }
    .authorselector .text_field{
        width: 350px;
        height: 20px;
        font-size: 16px;
        
    }
    .authorselector .btn{
        width: 150px;
        height: 30px;
        color: blue;
    }
    
</style>

<script type="text/javascript">
    function Author(){  
        
    }
    Author.prototype.getFullname=function(){ return [this.surname, this.name, this.fname].join(' '); };
    
    $(document).ready(function(){
     
    var list=<?=json_encode($this->selected)?>;
    list.map(function(a){
        a.__proto__= Author.prototype;  
    });
     
    
    function showSelected(){
        var s='';
        for(var i=0; i<list.length; i++){
            var a=list[i];
            var f='<p class="selected_item"><span>' + a.getFullname() + '</span><input type="button" value="X" class="author_rm"></p>';
            s=s+f;
        }
        $(".selected_authors").append(s);
        if (list.length==0)
            $('.selected_authors .zero_count').css('display', 'block');
        else $('.selected_authors .zero_count').css('display', 'none');
    }
    
    showSelected();

        
    $(document).on('submit', 'form', function(){
    $(this).find('.authorselector input[name=authors]').val(JSON.stringify(list));  
    });
    
    
    function add_item(a){
        if (list.map(function(e){return e.getFullname()}).indexOf(a.getFullname())!=-1){
            alert('Этот автор уже выбран!');
            return;
        }
        list.push(a);
        var s='<p class="selected_item"><span>' + a.getFullname() + '</span><input type="button" value="X" class="author_rm"></p>';
        $(".selected_authors").append(s);
        $('.selected_authors .zero_count').css('display', 'none');
    }
    
    function remove_item(btn){
        var item = btn.parent()
        var origin_fullname = item.children('span').text();
        item.remove();
        for(var i=0; i<list.length; i++){
            var a = list[i];
            var fullname=a.getFullname();
            if (fullname==origin_fullname){
                list.splice(list.indexOf(a), 1);
                
            }
        }
        if (list.length==0){
            $('.selected_authors .zero_count').css('display', 'block');
        }
    }
    
    
    $(document).on('click', '.author_rm', function(){
        remove_item($(this));
    }); 
    
    $('.sresult').slideUp(0);//!заметно
    
    $('input[name=author_surname]').on('input', function(){
        $('.sresult').slideUp();
        searched_a=null;
    });
    
    var sresult_list=[];
    var searched_a;
        
    $("#search").click(function(){ 
        var s = encodeURIComponent($('input[name=author_surname]').val());
        var ajax = $.ajax({
        url: "<?=get_abs_path('index.php')?>?a=authors&surname="+s,
        beforeSend: function ( xhr ) { }
        });
        
        ajax.done(function (data) {
            if (typeof data ==='string')
                data=JSON.parse(data);
            var li='';
            sresult_list=[];
            for(var i=0; i<data.length; i++){
                a=data[i];
                a.__proto__= Author.prototype;
                li=li+'<input type="button" value="+" class="item" data_pos="'+i+'">'+a.getFullname()+'<br>';
                sresult_list.push(a);
            }            
            $('.sresult').empty().append(li).slideDown();
        });      
    });
    
    $(document).on('click', '.sresult .item', function(){
        var i = $(this).attr('data_pos');
        $('.sresult').slideUp();
        var a = sresult_list[i];
        $('input[name=author_name]').val(a.name);
        $('input[name=author_surname]').val(a.surname);
        $('input[name=author_fname]').val(a.fname);
        searched_a=a;
    });
    
    $(".add_author").click(function(){
        if (searched_a)
        {
            add_item(searched_a);
            searched_a=null;
        }
        else
        {
            var author=new Author();
            author.name=$('input[name=author_name]').val().trim();
            author.fname=$('input[name=author_fname]').val().trim();
            author.surname=$('input[name=author_surname]').val().trim();
            if (!(author.name || author.surname || author.faname)){
                alert('Заполните поля для добавления автора');
                return;
            }
            add_item(author);
        }
        $('input[name=author_name]').val('');
        $('input[name=author_surname]').val('');
        $('input[name=author_fname]').val('');
    });
    
   
  
    
    });
    
   
</script>

<?php  } 

public function setAuthors($authors){
    $this->selected=is_array($authors)?$authors:[];
}
    
public function getAuthors($env){
    $authors=json_decode(@$env['authors']);
    $list=[];
    if (is_array($authors)){
        foreach($authors as $adata){
            $author=new Author();
            $properties=['id', 'name', 'fname', 'surname'];
            foreach($properties as $prop){
                $author->$prop=isset($adata->$prop)?$adata->$prop:NULL;
            }
            $list[]=$author;
        }
    }
    return $list;
}
    
    
}
