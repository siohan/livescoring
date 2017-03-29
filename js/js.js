{cms_jquery}
{literal}
<script>
$( document ).ready(function()
{
    var refreshID = setInterval( function() {
        $.ajax({
            type: 'GET',
            url: 'http://localhost:8888/livescoring/modules/Livescoring/include/checkRefresh.php',
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
</script>
{/literal}