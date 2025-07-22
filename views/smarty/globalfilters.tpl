[{$smarty.block.parent}]

<script>
    if (undefined === window.EnderecoIntegrator) {
        window.EnderecoIntegrator = {};
    }

    if (!window.EnderecoIntegrator.onLoad) {
        window.EnderecoIntegrator.onLoad = [];
    }

    if (!window.EnderecoIntegrator.$globalFilters) {
        window.EnderecoIntegrator.$globalFilters = {
            anyActive: [],
            anyMissing: [],
            isModalAreaFree: []
        };
    }

    window.EnderecoIntegrator.$globalFilters.isModalAreaFree.push(function(areaFree, reference) {
        if (!window.EnderecoIntegrator.ready) {
            return false;
        } else {
            return areaFree;
        }
    });

    function enderecoInitAMS(prefix, config, cb, withDelay = false) {
        const initializeAMS = () => {
            const EAO = window.EnderecoIntegrator.initAMS(prefix, config);
            if (cb) {
                cb(EAO);
            }
        };

        const executeInit = () => {
            if (!withDelay) {
                initializeAMS();
            } else {
                setTimeout(initializeAMS, 1000);
            }
        };

        if (window.EnderecoIntegrator.initAMS) {
            executeInit();
        } else {
            window.EnderecoIntegrator.onLoad.push(executeInit);
        }
    }
</script>

