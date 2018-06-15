<script>
    var jsonp = require('jsonp');
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
                url: {
                    api: 'http://uba-ckan.ext.datengrab.cc/api/3/action/',
                    package_list: 'package_list',
                    package_show: 'package_show?id=',
                },
                data: {
                    indikator: null,
                }
            }
        },
        created() {
            this.getData(this.url.api+this.url.package_list);
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
                $.get( url, function( response ) {
                    vm.processRespose(response.result);
                }, 'jsonp');
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
                        console.log(typeof(mergedFilter));
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
            processRespose(data) {
                if( data.length > 0 ) {
                    let vm = this;
                    $.each(data, function(dKey,dId) {
                        let result = $.get( vm.url.api+vm.url.package_show+dId, function( response ) {
                            $.each(response.result.resources, function(key,resource) {
                                console.log(resource);
                                vm.xhr.data.push({
                                    "title": resource.name,
                                    "source": resource.source,
                                    "reference": resource.spatial,
                                    "url": resource.url
                                });
                            })  ;
                            console.log(vm.xhr.data);
                        }, 'jsonp');
                    });
                    
                }
            }
        }
    }
</script>