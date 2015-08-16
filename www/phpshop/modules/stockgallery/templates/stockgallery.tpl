<script type="text/javascript" language="javascript" src="phpshop/modules/stockgallery/lib/jquery.js"></script>
<script type="text/javascript" language="javascript" src="phpshop/modules/stockgallery/lib/jquery.carouFredSel-5.6.1-packed.js"></script>
<script type="text/javascript" language="javascript">
    $(function() {
        // Basic carousel, no options
        $('#foo0').carouFredSel();

    });
</script>
<style>
    .wrapper {
        background-color: white;
        width: @stockgallery_width@px;
    }
    .list_carousel ul {
        margin: 0;
        padding: 0;
        list-style: none;
        display: block;
    }
    .list_carousel li {
        color: #999;
        text-align: center;
        background-color: #FFF;
        border: @stockgallery_border@px solid #@stockgallery_border_color@;
        width: @stockgallery_img_width@px;
        height: @stockgallery_img_height@px;
        padding: 0;
        margin: 3px;
        display: block;
        float: left;
    }


</style>
<div class="wrapper" align="center">
    <div class="list_carousel">
        <ul id="foo0">
      @stockgallery_list@
        </ul>
    </div>
</div>