<script>
    export default {
        data: function () {
            return {
                filter: {
                    source: [],
                    reference: [],
                },
                xhr: {
                    data: [],
                    errors: [],
                },
                data: {
                    indikator: null,
                }
            }
        },
        created() {
            this.getData('http://uba-ckan.ext.datengrab.cc/api/indicators');
        },
        filters: {
            indikator: function (data) {
                if (!data) return '';
                return "(Basis: " + data + ")";
            },
        },
        computed: {
            token() {
                return $('meta[name="csrf-token"]').attr('content');
            }
        },
        methods: {
            getData(url) {
                if (!url) return false;
                const vm = this;
                $.ajax({
                    url: url,
                    success: function (data, status) {
                        vm.xhr.data = data;
                    },
                    error: function (xOptions, textStatus) {
                        console.log(xOptions);
                    }
                });
            },
            getUniqueList(sKey,data) {
                if (!sKey || !data) return false;
                let result = [];
                data.forEach(function(val,key) {
                    if(result.indexOf(val[sKey]) === -1) { result.push(val[sKey]); }
                });
                return result;
            },
            filteredItems(data, filters) {
                if (!data) return false;
                if(!filters) { return data; }

                let filtersAreZero = true;
                let mergedFilter = new Array();
                for(var i in filters) {
                    if( filters[i].length > 0 && filtersAreZero )
                    {
                        filtersAreZero = false;
                        mergedFilter = Object.assign(filters[i]);
                    };
                }
                if(filtersAreZero) { return data; };

                let filteredData = [];
                $.each(filters, function(fKey,fValue){
                    $.each(data, function(dKey,dValue){
                        if(mergedFilter.indexOf(dValue[fKey]) >= 0) {
                            filteredData.push(dValue);
                        };
                    });
                });
                return filteredData;
            },
        }
    }
</script>