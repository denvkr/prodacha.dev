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
					<span>�������: <span class="prod_articul">@productArt@</span></span>
				</div>
				<div class="parameters">
                @vendorDisp@
                
				
					<div class="addchart_block">
						<table>
							<tr>
								<td width="160"> <span class="prev_price">����: @productPriceRub@</span>
         
           </td>
								<td><span class="quantity">���-��: <input  class="quantity" id="n@productUid@" type="num" maxlength="5" size="3" value="1" name="n@productUid@" />
                               </span>
							   
  
							   </td>
							</tr>
							<tr>
								<td valign="top">  @ComStartCart@<span class="price"><span style="font-size:22px; text-transform:uppercase;">����</span> <div> @productPrice@ <span class="smallfont">@productValutaName@. </span></div></span> @ComEndCart@</td>
								<td valign="top" style="padding-top:38px">
                                
                              

                                
                                @ComStartCart@<span class="addtochart"><input type="button" onclick="javascript:AddToCartNum(@productUid@,'n@productUid@')" value="� �������" /></span>@ComEndCart@
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
		            <li><a href="#tab1">��������</a></li>
		            <li><a href="#tab2">�����</a></li>
		            <li><a href="#tab3">�������</a></li>
		            <li><a href="#tab4">������</a></li>
		            <li><a href="#tab5">������</a></li>
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
		
		           
		           <div id="bg_catalog_1" style="margin-top:10px">����������� �������������</div>
    <textarea id="message" style="width: 340px" rows="5" onkeyup="return countSymb();"></textarea>
    <div style="font-size: 10px; margin-bottom: 5px">������������ ���������� ��������: <span id="count" style="width: 30px; color: green; text-aling: center">0</span>/&nbsp;&nbsp;&nbsp;500 </div>
    <div style="padding: 5px"> <img onmouseover="this.style.cursor='hand';" title="�������" onclick="emoticon(':-D');" alt="�������" src="images/smiley/grin.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="���������" onclick="emoticon(':)');" alt="���������" src="images/smiley/smile3.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="��������" onclick="emoticon(':(');" alt="��������" src="images/smiley/sad.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="� ����" onclick="emoticon(':shock:');" alt="� ����" src="images/smiley/shok.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="�������������" onclick="emoticon(':cool:');" alt="�������������" src="images/smiley/cool.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="����������" onclick="emoticon(':blush:');" alt="����������" src="images/smiley/blush2.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="�������" onclick="emoticon(':dance:');" alt="�������" src="images/smiley/dance.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="��������" onclick="emoticon(':rad:');" alt="��������" src="images/smiley/happy.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="��� ������" onclick="emoticon(':lol:');" alt="��� ������" src="images/smiley/lol.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="� ��������������" onclick="emoticon(':huh:');" alt="� ��������������" src="images/smiley/huh.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="����������" onclick="emoticon(':rolly:');" alt="����������" src="images/smiley/rolleyes.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="����" onclick="emoticon(':thuf:');" alt="����" src="images/smiley/threaten.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="���������� ����" onclick="emoticon(':tongue:');" alt="���������� ����" src="images/smiley/tongue.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="��������" onclick="emoticon(':smart:');" alt="��������" src="images/smiley/umnik2.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="���������" onclick="emoticon(':wacko:');" alt="���������" src="images/smiley/wacko.gif" border="0" /> <img onmouseover="this.style.cursor='hand';" title="�����������" onclick="emoticon(':yes:');" alt="�����������" src="images/smiley/yes.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="���������" onclick="emoticon(':yahoo:');" alt="���������" src="images/smiley/yu.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="��������" onclick="emoticon(':sorry:');" alt="��������" src="images/smiley/sorry.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="��� ���" onclick="emoticon(':nono:');" alt="��� ���" src="images/smiley/nono.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="������ �� ������" onclick="emoticon(':dash:');" alt="������ �� ������" src="images/smiley/dash.gif" border="0"> <img onmouseover="this.style.cursor='hand';" title="������������" onclick="emoticon(':dry:');" alt="������������" src="images/smiley/dry.gif" border="0"> </div>
    <div style="padding:5px" id="commentButtonAdd">
      <input type="button"  value="�������� �����������" onclick="commentList('@productUid@','add',1)" />
    </div>
    <div  id="commentButtonEdit" style="padding:5px; visibility:hidden; display:none">
      <input type="button"  value="�������� �����������" onclick="commentList('@productUid@','add',1)" />
      <input type="button"  value="������� �����������" onclick="commentList('@productUid@','edit_add','1')" />
      <input type="button"  value="�������" onclick="commentList('@productUid@','dell','1')" />
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
