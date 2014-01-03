

<form action="/mvc/index.php/ProductLines" method="post" accept-charset="utf-8">
    <input type="hidden" name="aa" value="ciao" />
    <input type="hidden" name="aa2" value="ciao2" />
    <input type="submit" name="s" value="invia" />
</form>
<p>Ciao ciao</p>
<div id="quote">
    aa
</div><!-- / -->
<?php
    $this->load->helper('html',$this);
    echo "<br />";
    echo $this->html->createLink('titolo', "#", array('id'=>'link', 'class'=>'nuovo'));
    echo $this->html->createLink('link', $this->html->url('productLines', 'buttonClickedServer') );

    $this->html->table($data[$data['modelName']], array('table' => 'prova', 'thead' => 'header', 'tr' => 'row', 'td' => 'data', 'tdOdd' => 'dataOdd'));
?>
<form action="<?php echo $this->html->url('ProductLines','add') ?>" method="post" accept-charset="utf-8">
    <input type="text" name="line" value="" placeholder="">
    <input type="text" name="text" value="" placeholder="">
    <input type="submit" name="invia" value="invia">
</form>
