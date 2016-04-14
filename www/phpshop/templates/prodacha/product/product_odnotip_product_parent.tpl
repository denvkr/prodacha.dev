<table>
  <tr>
    <td id="td_forml"><div id="parent_list_info">Выберите модификацию:</div> @parentList@
    <span class="addtochart" style="margin-top:10px;margin-left:165px;"><a href="#_tool_@productUid@" id="a@productUid@" onclick="javascript:AddToCartParent(@productId@);">@php echo $GLOBALS['SysValue']['lang']['product_sale']; php@</a></span>
  </tr>
</table>
  <script type="text/javascript">
    $(document).ready(function (){
        if ($('.price_comlain.fast_order1').length) $('.price_comlain.fast_order1').css('display','none');
        if ($('.creditinputcart').length) $('.creditinputcart').css('display','none');
        if ($('.addchart_block_table_price:eq(0)').length){
            $('.addchart_block_table_price:eq(0)').css({"position": "absolute","width": "190px","margin-top": "-55px"});  
        }
        if ($('.addchart_block').length) $('.addchart_block').css('padding-top','0px');
        if ($('#parentId').length)
        product_price_change($('#parentId').val());
    });
  </script>