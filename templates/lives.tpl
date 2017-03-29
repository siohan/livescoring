<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>

{if $itemcount > 0}

<table class="pagetable table-bordered">
 <thead>
	<tr>
		<th>Statut</th>
		<th>Date</th>
		<th>Niveau</th>
		<th>Locaux</th>
		<th>Score</th>
		<th>Adversaire</th>
		<th colspan="3">Actions</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{if $entry->actif==0 && $entry->closed ==0}{$entry->actif}{elseif $entry->actif==0 && $entry->closed ==1}Terminé{else}En live !{/if}</td>
    <td>{$entry->date}</td>
	<td>{$entry->niveau}</td>
	<td>{$entry->locaux}</td>
	<td>{$entry->plus_locaux} {$entry->moins_locaux} {$entry->score_locaux} - {$entry->score_adversaires} {$entry->moins_adversaires} {$entry->plus_adversaires} </td>
	<td>{$entry->adversaires}  </td>
	<td>{$entry->direct}</td>
	{if $entry->closed ==0}<td>{$entry->live}</td>{/if}
	<td>{$entry->composition}</td>
	<!--	<td>{$entry->editlink}</td>
    <td>{$entry->deletelink}</td>-->
  </tr>
{/foreach}
 </tbody>
</table>
{else}
 	<p>pas encore de résultats</p>
{/if}
