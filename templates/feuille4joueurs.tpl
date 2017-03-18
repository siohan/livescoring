<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>

{if $itemcount > 0}
<table class="pagetable table-bordered">
 <thead>
	<tr>
		<th>Partie</th>
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
	<td>{$entry->joueur1}</td>
	<td>{if $entry->statut == 2}{$entry->vicA}{else} {$entry->vicA}{$entry->plus1}{/if}</td>
	<td>{$entry->vicW}</td>
	<td>{$entry->joueur2}</td>
	<td>{$entry->live}</td>

  </tr>
{/foreach}
 </tbody>
</table>

{/if}