<!-- RIBBON -->
<?php echo $this->Element('admin/breadcrumb'); ?>
<!-- END RIBBON -->

<div id="content">
    <?php echo $this->Form->msg($this->Session->flash()); ?>
    
    <div class="row src_iframe" >
      <?php 
        $iframe = '<iframe src="'.$url.'" width="100%" height="600" border="0" style="border:none;" />';
      ?>
    </div>

 
    
    
    
</div>


<script type="text/javascript">

    // ENTRAR FINALIZADO
    $(document).ready(function(){
      iframe = '<?php echo $iframe;?>';
      var parentHeight = $(parent).height() - 100;
      //var alturaDesejada = parentHeight * 1; // 100% é o mesmo que 1
      
      var iframe2 = iframe.replace('height="600"', 'height="'+parentHeight+'"');
        setTimeout(function(){
            $("body").removeClass("minified");
            $('.minifyme').click();
        },1000);

        setTimeout(function(){
            $('.src_iframe').html(iframe2);
        },1500);
        

        
    })
    
</script>