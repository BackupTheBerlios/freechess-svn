<!-- TODO:  SITE SPECIFIC LOGO, PUT IN CONFIG option, else, default -->

<div align="center">
<img src="images/compwebchess.gif" alt="website_logo">
{if $errors}
<br><br>
<font color="red">
{section name=a loop=$errors}
{$errors[a]}<br>
{/section}
{/if}
</div>

<form action="new_player.php" method="post" name="signup">
<table border="0" cellspacing="1" cellpadding="1" bgcolor="#cfcfbb" align="center">
<tr>
<td width="100%" colspan="2" align="center">
        {$lang.new_player_line1}
        {$lang.new_player_line2}
</td>
</tr>
<tr>
<td width="50%" align="right">
{$lang.new_player_username} :
</td>
<td width="50%" align="left">
<input type="text" name="username" value="{$postvar.username}" maxlength="20" size="25">
</td>
</tr>               
<tr>
<td width="50%" align="right">
{$lang.new_player_pass1} :
</td>
<td width="50%" align="left">
<input type="password" name="pass1" value="" maxlength="20" size="25">
</td>
</tr>
<tr>
<td width="50%" align="right">
{$lang.new_player_pass2} :
</td>
<td width="50%" align="left">
<input type="password" name="pass2" value="" maxlength="20" size="25">
</td>
</tr>
<tr>
<td width="50%" align="right">
{$lang.new_player_email} :
</td>
<td width="50%" align="left">
<input type="text" name="email" value="{$postvar.email}" maxlength="80" size="50">
</td>
</tr>
<tr>
<td width="50%" align="right">
{$lang.new_player_firstname} :
</td>
<td width="50%" align="left">
<input type="text" name="first_name" value="{$postvar.first_name}" maxlength="50" size="50">
</td>
</tr> 
<tr>
<td width="50%" align="right">
{$lang.new_player_lastname} :
</td>
<td width="50%" align="left">
<input type="text" name="last_name" value="{$postvar.last_name}" maxlength="50" size="50">
</td>
</tr>
<tr>
<td width="50%" align="right">
{$lang.new_player_sex} :
</td>
<td width="50%" align="left">
<input type="radio" name="sex" value="M" {if $postvar.sex eq "M"}CHECKED{/if}> {$lang.new_player_male}&middot; 
<input type="radio" name="sex" value="F" {if $postvar.sex eq "F"}CHECKED{/if}> {$lang.new_player_female}&middot; 
<input type="radio" name="sex" value="U" {if $postvar.sex eq "U"}CHECKED{/if}> {$lang.new_player_neutral} 
</td>
</tr>
<tr>
<td width="50%" align="right">
{$lang.new_player_city} :
</td>
<td width="50%" align="left">
<input type="text" name="city" value="{$postvar.city}" maxlength="50" size="50">
</td>
</tr>
<tr>
<td width="50%" align="right">
{$lang.new_player_state} :
</td>
<td width="50%" align="left">
<select name="state">
<option value="">{$lang.new_player_choose_state}</option>
<option value="00">{$lang.new_player_other}</option>
{html_options values=$state_code output=$state_names selected=$postvar.state}
</select>
</td>
</tr>
<tr>
<td width="50%" align="right">
{$lang.new_player_country} :
</td>
<td width="50%" align="left">
<select name="country">
<option value="">{$lang.new_player_choose_country}</option>
<option value="00">{$lang.new_player_other}</option>
{html_options values=$country_code output=$country_list selected=$postvar.country}
</select>
</td>
</tr>
<tr>
<td width="50%" align="right">
{$lang.new_player_birthday} :
</td>
<td width="50%" align="left">

{html_select_date prefix="birth_" time=$time start_year="-100" end_year="+1" field_order=$lang.date_order}


</td>
</tr>
<tr>
<td width="100%" align="center">
<input type="submit" name="submit" value="{$lang.submit}">
</td>
</tr>

    </table>
</form>
</div>

