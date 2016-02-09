	<div class="pagetitle">

					<span> @productName@ </span>
                                  
				</div>
               <div style="clear:both"></div>
<div class="productbox">
				<div class="prod_show_box">
                 <div id="fotoload" align="center" >@productFotoList@</div>
				
					
				</div>
				<div class="prod_parm_col">
				<div class="articul">
					<span>Артикул: <span class="prod_articul">@productArt@</span></span>
				</div>
				<div class="parameters">
                @vendorDisp@
                
				
					<div class="addchart_block">
						<table>
							<tr>
								<td width="160"> <span class="prev_price">Цена: @productPriceRub@</span>
         
           </td>
								<td><span class="quantity">Кол-во: <input  class="quantity" id="n@productUid@" type="num" maxlength="5" size="3" value="1" name="n@productUid@" />
                               </span>
							   
  
							   </td>
							</tr>
							<tr>
								<td valign="top">  @ComStartCart@<span class="price"><span style="font-size:22px; text-transform:uppercase;">Цена</span> <div> @productPrice@ <span class="smallfont">@productValutaName@. </span></div></span> @ComEndCart@</td>
								<td valign="top" style="padding-top:38px">
                                
                              

                                
                                @ComStartCart@<span class="addtochart"><input type="button" onclick="javascript:AddToCartNum(@productUid@,'n@productUid@')" value="В корзину" /></span>@ComEndCart@
@ComStartNotice@<span class="addtochart"><input type="button" onclick="window.location.replace('/users/notice.html?productId=@productUid@');"  value="@productNotice@" />
                            </span>@ComEndNotice@</td>
							</tr>
						</table>
						 @ComStart@
				 <div class="tovar_optionsDisp">@optionsDisp@</div>@ComEnd@
                 							 @productParentList@
					</div>	
				</div>
				
				</div>
			</div>
            
            <div class="tabs">
		        <ul class="tabNavigation">
		            <li><a href="#tab1">Описание</a></li>
		            <li><a href="#tab2">Файлы</a></li>
		            <li><a href="#tab3">Рейтинг</a></li>
		            <li><a href="#tab4">Отзывы</a></li>
		            <li><a href="#tab5">Статьи</a></li>
		        </ul>
		        <div id="tab1">
		          @productDes@
		        </div>
		        <div id="tab2">
		    @productFiles@
		        </div>
		        <div id="tab3">
		            @ratingfull@
		        </div>
		        <div id="tab4">
		
		           
		           <div id="bg_catalog_1" style="margin-top:10px">Комментарии пользователей</div>
    <textarea id="message" style="width: 340px" rows="5" onkeyup="return countSymb();"></textarea>
    <div style="font-size: 10px; margin-bottom: 5px">Максимальное количество символов: <span id="count" style="width: 30px; color: green; text-aling: center">0</span>/&nbsp;&nbsp;&nbsp;500 </div>
    <div style="padding: 5px"> <img onmouseover="this.style.cursor='hand';" title="Смеется" onclick="emoticon(':-D');" alt="Смеется" src="images/smiley/grin.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="Улыбается" onclick="emoticon(':)');" alt="Улыбается" src="images/smiley/smile3.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="Грустный" onclick="emoticon(':(');" alt="Грустный" src="images/smiley/sad.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="В шоке" onclick="emoticon(':shock:');" alt="В шоке" src="images/smiley/shok.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="Самоуверенный" onclick="emoticon(':cool:');" alt="Самоуверенный" src="images/smiley/cool.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="Стесняется" onclick="emoticon(':blush:');" alt="Стесняется" src="images/smiley/blush2.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="Танцует" onclick="emoticon(':dance:');" alt="Танцует" src="images/smiley/dance.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="Счастлив" onclick="emoticon(':rad:');" alt="Счастлив" src="images/smiley/happy.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="Под столом" onclick="emoticon(':lol:');" alt="Под столом" src="images/smiley/lol.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="В замешательстве" onclick="emoticon(':huh:');" alt="В замешательстве" src="images/smiley/huh.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="Загадочный" onclick="emoticon(':rolly:');" alt="Загадочный" src="images/smiley/rolleyes.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="Злой" onclick="emoticon(':thuf:');" alt="Злой" src="images/smiley/threaten.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="Показывает язык" onclick="emoticon(':tongue:');" alt="Показывает язык" src="images/smiley/tongue.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="Умничает" onclick="emoticon(':smart:');" alt="Умничает" src="images/smiley/umnik2.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="Запутался" onclick="emoticon(':wacko:');" alt="Запутался" src="images/smiley/wacko.gif" border="0" /> <img onmouseover="this.style.cursor='hand';" title="Соглашается" onclick="emoticon(':yes:');" alt="Соглашается" src="images/smiley/yes.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="Радостный" onclick="emoticon(':yahoo:');" alt="Радостный" src="images/smiley/yu.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="Сожалеет" onclick="emoticon(':sorry:');" alt="Сожалеет" src="images/smiley/sorry.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="Нет Нет" onclick="emoticon(':nono:');" alt="Нет Нет" src="images/smiley/nono.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="Бьется об стенку" onclick="emoticon(':dash:');" alt="Бьется об стенку" src="images/smiley/dash.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="Скептический" onclick="emoticon(':dry:');" alt="Скептический" src="images/smiley/dry.gif" border="0"> </div>
    <div style="padding:5px" id="commentButtonAdd">
      <input type="button"  value="Добавить комментарий" onclick="commentList('@productUid@','add',1)" />
    </div>
    <div  id="commentButtonEdit" style="padding:5px; visibility:hidden; display:none">
      <input type="button"  value="Добавить комментарий" onclick="commentList('@productUid@','add',1)" />
      <input type="button"  value="Править комментарий" onclick="commentList('@productUid@','edit_add','1')" />
      <input type="button"  value="Удалить" onclick="commentList('@productUid@','dell','1')" />
      <input type="hidden" id="commentEditId" />
    </div>
    <div id="commentList"> </div>
    <script>
            setTimeout("commentList('@productUid@','list')",500);
        </script>
		        </div>
	          <div id="tab5">
		     
		           @pagetemaDisp@
		        </div>
                <img src="../images/spacer.gif" width="783" height="1" alt="" />

		    </div>
