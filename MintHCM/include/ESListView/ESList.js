class ESList {
    constructor(defs, module) {
        this.defs = defs.columns;
        this.module = module;
        this.component = document.querySelector('es-list').vueComponent;
        let wrapper = [];
        wrapper.push(this.defs);
        this.defs = wrapper;
    }

    init() {
        this.setEvents();
        this.setLabels(this.defs, this.module);
        this.setHeaders();
        this.getResults({ 'page': 1, 'itemsPerPage': 10, sortBy: '' });
        this.getMappings();
    }

    setEvents() {
        this.component.$on('getResults', (params) => {
            this.getResults(params)
        });
        this.component.$on('deleteAll', (data) => {
            data.page = 1;
            data.itemsPerPage = 10000;
            this.getIdsForMassUpdate(data);
        });
        this.component.$on('deleteThisPage', (data) => {
            this.massUpdate(data.IDs);
        });
    }

    setLabels(defs, module) {
        let sugarLabels = SUGAR.language.languages[module];

        for (let value of Object.values(defs[0])) {
            value.label = sugarLabels[value.label];
        }
        this.component.$data.defs = this.defs;

        return;
    }

    setHeaders() {
        let keys = Object.keys(this.defs[0]);
        let slots = [];
        let headers = [];
        let item = {};

        for (let i = 0; i < keys.length; i++) {
            let key = keys[i];

            if (this.defs[0][key].default) {
                item.text = this.defs[0][key].label;
                item.value = keys[i].toLocaleLowerCase();

                if (this.defs[0][key].link) {
                    slots.push(keys[i].toLocaleLowerCase());
                }
                if (this.defs[0][key].sortable || !('sortable' in this.defs[0][key])) {
                    item.sortable = true;
                } else {
                    item.sortable = false;
                }

                headers.push(item);
                item = {};
            }
        }

        headers.push({ text: 'Akcje', value: 'Akcje', sortable: false, align: 'end' });

        this.component.$data.headers = headers;
        this.component.$data.slots = slots;

        return;
    }

    getMappings() {
        viewTools.api.callController({
            module: this.module,
            action: 'ESList',
            dataGET: { module: this.module },
            dataPOST: { function_name: 'getMappings' },
            callback: function (data) {
                this.mappings = Object.values(JSON.parse(JSON.parse(data)))[0].mappings[this.module].properties;
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

    getResults(params) {
        let viewToolsParams = this.setParams(params);

        viewTools.api.callController({
            module: this.module,
            action: 'ESList',
            dataGET: viewToolsParams,
            dataPOST: { function_name: 'getResults' },
            callback: function (data) {
                data = JSON.parse(data);

                this.component.$data.totalResults = data.total;
                this.component.$data.results = data.results;
            }.bind(this)
        });
    }

    setParams(params) {
        let viewToolsParams = {};

        viewToolsParams.myObjects = 'myObjects' in params ? params.myObjects : false;
        viewToolsParams.searchPhrase = 'searchPhrase' in params ? params.searchPhrase : '';
        viewToolsParams.page = params.page;
        viewToolsParams.itemsPerPage = params.itemsPerPage;
        if (params.sortBy[0]) {
            let sortBy = this.fieldNameInMappings(params.sortBy[0]);
            viewToolsParams.sortBy = sortBy;
            viewToolsParams.sortOrder = params.sortDesc[0] ? 'desc' : 'asc';
        }

        return viewToolsParams;
    }

    fieldNameInMappings(column) {
        let fieldName;
        if (column == 'name') fieldName = 'named';
        else if (this.mappings.hasOwnProperty(column)) fieldName = column;

        else if (column == 'date_entered') return 'meta.created.date';
        else if (column == 'created_by') return 'meta.created.user_id.keyword';
        else if (column == 'date_modified') return 'meta.modified.date';
        else if (column == 'modified_user_id') return 'meta.modified.user_id.keyword';
        else if (column == 'assigned_user_id') return 'meta.assigned.user_id.keyword';
        else if (column == 'modified_by_name') return 'meta.modified.user_name.keyword';
        else if (column == 'created_by_name') return 'meta.created.user_name.keyword';
        else if (column == 'assigned_user_name') return 'meta.assigned.user_name.keyword';
        else {
            console.log('wartosc niestandardowa');
            return '';
        }

        if (this.mappings[fieldName].type === 'date') {
            return fieldName;
        } else {
            fieldName = fieldName + '.keyword';
            return fieldName;
        }
    }
}

window.ESList = ESList;
