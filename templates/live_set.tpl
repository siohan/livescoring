<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
{$retour}
{if $itemcount > 0}
<table class="pagetable table-bordered">
 <thead>
	<tr>
		<th>Partie</th>
		<th>Set</th>
		<th>Joueur A</th>
		<th>Victoire A</th>
		<th>Victoire W</th>
		<th>Joueur W</th>		
		<th>Live !</th>
	
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->partie}</td>
	<td>{$entry->numero_set}
	<td>{$entry->joueur1}</td>
	<td>{if $entry->statut ==2}{$entry->scoreA}{else}{$entry->moinsA1} {$entry->scoreA} {$entry->plusA1}{/if}</td>
	<td>{$entry->moinsW1} {$entry->scoreW} {$entry->plusW1}</td>
	<td>{$entry->joueur2}</td>
	{if $entry->set_end ==1}
	<td>{$entry->fin_set}</td>
	{/if}
	
	

  </tr>
{/foreach}
 </tbody>
</table>

{/if}