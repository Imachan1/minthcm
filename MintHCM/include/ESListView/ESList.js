class ESList {
    constructor() {
        this.component = document.querySelector('es-list').vueComponent;
        this.columns = this.component.$store.state.columns;
        this.module = this.component.$store.state.module;
    }

    init() {
        this.setEvents()
        this.getResults()
        this.getMappings()
    }

    setEvents() {
        this.component.$root.$on('getResults', this.getResults.bind(this));
        this.component.$root.$on('deleteAll', (data) => {
            data.page = 1;
            data.itemsPerPage = 10000;
            this.getIdsForMassUpdate(data);
        });
        this.component.$root.$on('deleteThisPage', (data) => {
            this.massUpdate(data.IDs);
        });
    }

    getMappings() {
        viewTools.api.callController({
            module: this.module,
            action: 'ESList',
            dataGET: { module: this.module },
            dataPOST: { function_name: 'getMappings' },
            callback: function (data) {
                this.mappings = Object.values(JSON.parse(data))[0].mappings[this.module].properties;
            }.bind(this)
        });
    }

    getIdsForMassUpdate(params) {
        viewTools.api.callController({
            module: this.module,
            action: 'ESList',
            dataGET: params,
            dataPOST: { function_name: 'getIDsForMassUpdate' },
            callback: function (data) {
                data = JSON.parse(data);
                this.massUpdate(data.Calls);
            }.bind(this)
        });
    }

    massUpdate(IDs) {
        viewTools.api.callController({
            module: this.module,
            action: 'ESList',
            dataGET: {},
            dataPOST: { function_name: 'massUpdate', IDs: IDs, action_name: 'delete' },
            callback: function (data) {
                data = JSON.parse(data);
                if (data.success) {
                    location.reload();
                }
            }.bind(this)
        });               
    }

    getResults() {
        viewTools.api.callController({
            module: this.module,
            action: 'ESList',
            dataGET: this.getParams(),
            dataPOST: {
                function_name: 'getResults',
                filters: this.component.$store.state.options.filters
            },
            callback: function (data) {
                data = JSON.parse(data);
                this.component.$store.commit('setData', {
                    records: data.results || [],
                    total: data.total || 0,
                })
                this.component.$store.commit('setOptions', {
                    offset: data.offset || 0,
                })
            }.bind(this)
        });
    }

    getParams() {
        const options = this.component.$store.state.options
        const params = {
            page: options.page,
            itemsPerPage: options.itemsPerPage,
            myObjects: !!options.myObjects,
            searchPhrase: options.searchPhrase ?? '',
            offset: options.page === 1 ? 0 : options.offset,
        }
        if (options.sortBy) {
            params.sortBy = this.fieldNameInMappings(options.sortBy);
            params.sortOrder = options.sortOrder;
        }
        return params;
    }

    fieldNameInMappings(column) {
        const map = {
            name: 'name.name.keyword',
            primary_address_city: 'address.alt.city.keyword',
            primary_address_state: 'address.alt.state.keyword',
            primary_address_postalcode: 'address.alt.postalcode.keyword',
            primary_address_street: 'address.alt.street.keyword',
            primary_address_country: 'address.alt.country.keyword',
            first_name: 'name.first.keyword',
            last_name: 'name.last.keyword',
            date_entered: 'meta.created.date',
            created_by: 'meta.created.user_id.keyword',
            date_modified: 'meta.modified.date',
            modified_user_id: 'meta.modified.user_id.keyword',
            assigned_user_id: 'meta.assigned.user_id.keyword',
            modified_by_name: 'meta.modified.user_name.keyword',
            created_by_name: 'meta.created.user_name.keyword',
            assigned_user_name: 'meta.assigned.user_name.keyword',
            phone_mobile: 'phone.mobile.keyword',
        }
        if (map[column]) {
            return map[column]
        } else if (this.mappings[column]) {
            return ['date', 'boolean'].includes(this.mappings[column].type) ? column : `${column}.keyword`
        }
        return ''
    }
}

window.ESList = ESList;
