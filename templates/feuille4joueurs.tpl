<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
{$retour}
{if $itemcount > 0}
<table class="pagetable table-bordered">
 <thead>
	<tr>
		<th>Partie</th>
		<th>Joueur A</th>
		<th>A</th>
		<th>W</th>
		<th>Joueur W</th>
		<th>Score</th>		
		<th>Live !</th>
	
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->partie}</td>
	<td>{$entry->joueur1}</td>
	<td>{if $entry->statut == 2}{$entry->vicA}{else} {$entry->vicA}  {$entry->plusA1}{/if}</td>
	<td>{if $entry->statut == 2}{$entry->vicW}{else}{$entry->plusW1}  {$entry->vicW}{/if}</td>
	<td>{$entry->joueur2}</td>
	<td>{$entry->score}</td>
	<td>{$entry->live}</td>

  </tr>
{/foreach}
 </tbody>
</table>

{/if}