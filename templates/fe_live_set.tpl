{literal}
<script type="text/javascript">
//<![CDATA[
$( document ).ready(function()
{
    var refreshID = setInterval( function() {
        $.ajax({
            type: 'GET',
            url: '{/literal}{root_url}{literal}/modules/Livescoring/include/checkRefresh.php',
            dataType: 'html',
            success: function(html, textStatus) {
                 //Handle the return data (1 for refresh, 0 for no refresh)
                if(html == 1)
                {
                    location.reload();
                }
            }
            ,
            error: function(xhr, textStatus, errorThrown) {
                alert(errorThrown?errorThrown:xhr.status);
            }
        });
    }, (5000 )); //Poll every 5 seconds.
});
//]]>
</script>
{/literal}
<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
{$retour}

{if $itemcount > 0}
<div id="contenuTable">
<table class="pagetable table-bordered">
 <thead>
	<tr>
		<th>Partie</th>
		<th>Set</th>
		<th>Joueur A</th>
		<th>Pts A</th>
		<th>Pts W</th>
		<th>Joueur W</th>		
		
	
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->partie}</td>
	<td>{$entry->numero_set}
	<td>{$entry->joueur1}{if $entry->affichage_service =="A"}{$entry->image_service}{/if}</td>
	<td>{$entry->scoreA}</td>
	<td>{$entry->scoreW}</td>
	<td>{if $entry->affichage_service =="W"}{$entry->image_service}{/if}{$entry->joueur2}</td>
  </tr>
{/foreach}
 </tbody>
</table>
</div>
{/if}
