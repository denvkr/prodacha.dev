<div class="page_nava">
  <div> <a href="/">PRO����</a> / ����������� ����� </div>
</div>
	<div class="pagetitle">
					<span><h1>����������� �����</h1></span>
				</div>


<FORM method="post" name="forma_search">
  <table>
    <tr>
      <td>������� �����:<br>
        <INPUT style="WIDTH:550px" maxLength="100" name="words" value="@searchString@">
      </td>
      <td><br>
        <input type="submit" value="������" class="ok">
      </td>
    </tr>
    <tr>
      <td colspan="2">�������� �������:<br>
        @searchPageCategory@ </td>
    </tr>
    <tr>
      <td colspan="2" id="sort"><table cellpadding="0" cellspacing="0">
          <tr>@searchPageSort@</tr>
        </table></td>
    </tr>
    <tr>
      <td colspan="2"><b>������ ������:</b>
        <input type="Radio" value="1" name="set" @searchSetA@>
        � &nbsp;
        <input type="Radio" value="2" name="set" @searchSetB@ >
        ���
        &nbsp;&nbsp;&nbsp;/ &nbsp;&nbsp;<b>�������:</b>
        <input type="Radio" value="1" name="pole" @searchSetC@>
        ������������ &nbsp;
        <input type="Radio" value="2" name="pole" @searchSetD@ >
        ��������� ��� </td>
    </tr>
  </table>
</FORM>
<p><br>
</p>	

<div class="sorting">
<div class="content">
<div class="wrapper">
             <table cellpadding="0" cellspacing="0" border="0" >
  @productPageDis@
</table></div>
</div>
</div>
                
			
				</div>

<div class="navi">@searchPageNav@</div>
