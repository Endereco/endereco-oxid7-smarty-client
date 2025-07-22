[{$smarty.block.parent}]

<script>
    ( function() {
        var $interval = setInterval( function() {
            if (window.EnderecoIntegrator && window.EnderecoIntegrator.ready) {
                window.EnderecoIntegrator.initEmailServices(
                    '',
                    {
                        "name": "default",
                        "postfixCollection": {
                            "email": "#content [name=\"lgn_usr\"]"
                        }
                    }
                );
                clearInterval($interval);
            }
        }, 100);
    })();
</script>
