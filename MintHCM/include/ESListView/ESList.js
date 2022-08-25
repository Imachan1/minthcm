class ESList {
    constructor(defs, module) {
        this.defs = defs;
        this.module = module;
        this.component = document.querySelector('es-list').vueComponent;
        // this.url = 'https://bartanowiczs71.int.evolpe.net/minthcm/index.php?entryPoint=elasticSearchTests&module=Calls';
        this.mappings = {
            "3a5a56a18649bae4f6e1e669917855d7_shared": {
                "mappings": {
                    "Calls": {
                        "_meta": {
                            "last_index": "2022-08-21 23:24:40"
                        },
                        "properties": {
                            "date_end": {
                                "type": "text",
                                "fields": {
                                    "keyword": {
                                        "type": "keyword",
                                        "ignore_above": 256
                                    }
                                }
                            },
                            "date_start": {
                                "type": "text",
                                "fields": {
                                    "keyword": {
                                        "type": "keyword",
                                        "ignore_above": 256
                                    }
                                }
                            },
                            "description": {
                                "type": "text",
                                "fields": {
                                    "keyword": {
                                        "type": "keyword",
                                        "ignore_above": 256
                                    }
                                }
                            },
                            "direction": {
                                "type": "text",
                                "fields": {
                                    "keyword": {
                                        "type": "keyword",
                                        "ignore_above": 256
                                    }
                                }
                            },
                            "duration_hours": {
                                "type": "text",
                                "fields": {
                                    "keyword": {
                                        "type": "keyword",
                                        "ignore_above": 256
                                    }
                                }
                            },
                            "duration_minutes": {
                                "type": "text",
                                "fields": {
                                    "keyword": {
                                        "type": "keyword",
                                        "ignore_above": 256
                                    }
                                }
                            },
                            "email_reminder_sent": {
                                "type": "text",
                                "fields": {
                                    "keyword": {
                                        "type": "keyword",
                                        "ignore_above": 256
                                    }
                                }
                            },
                            "email_reminder_time": {
                                "type": "text",
                                "fields": {
                                    "keyword": {
                                        "type": "keyword",
                                        "ignore_above": 256
                                    }
                                }
                            },
                            "meta": {
                                "properties": {
                                    "assigned": {
                                        "properties": {
                                            "user_id": {
                                                "type": "text",
                                                "fields": {
                                                    "keyword": {
                                                        "type": "keyword",
                                                        "ignore_above": 256
                                                    }
                                                }
                                            },
                                            "user_name": {
                                                "type": "text",
                                                "fields": {
                                                    "keyword": {
                                                        "type": "keyword",
                                                        "ignore_above": 256
                                                    }
                                                }
                                            }
                                        }
                                    },
                                    "created": {
                                        "properties": {
                                            "date": {
                                                "type": "date",
                                                "format": "yyyy-MM-dd HH:mm:ss"
                                            },
                                            "user_id": {
                                                "type": "text",
                                                "fields": {
                                                    "keyword": {
                                                        "type": "keyword",
                                                        "ignore_above": 256
                                                    }
                                                }
                                            },
                                            "user_name": {
                                                "type": "text",
                                                "fields": {
                                                    "keyword": {
                                                        "type": "keyword",
                                                        "ignore_above": 256
                                                    }
                                                }
                                            }
                                        }
                                    },
                                    "modified": {
                                        "properties": {
                                            "date": {
                                                "type": "text",
                                                "fields": {
                                                    "keyword": {
                                                        "type": "keyword",
                                                        "ignore_above": 256
                                                    }
                                                }
                                            },
                                            "user_id": {
                                                "type": "text",
                                                "fields": {
                                                    "keyword": {
                                                        "type": "keyword",
                                                        "ignore_above": 256
                                                    }
                                                }
                                            },
                                            "user_name": {
                                                "type": "text",
                                                "fields": {
                                                    "keyword": {
                                                        "type": "keyword",
                                                        "ignore_above": 256
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            },
                            "name": {
                                "properties": {
                                    "first": {
                                        "type": "text",
                                        "fields": {
                                            "keyword": {
                                                "type": "keyword",
                                                "ignore_above": 256
                                            }
                                        },
                                        "copy_to": [
                                            "named"
                                        ]
                                    },
                                    "last": {
                                        "type": "text",
                                        "fields": {
                                            "keyword": {
                                                "type": "keyword",
                                                "ignore_above": 256
                                            }
                                        },
                                        "copy_to": [
                                            "named"
                                        ]
                                    },
                                    "name": {
                                        "type": "text",
                                        "fields": {
                                            "keyword": {
                                                "type": "keyword",
                                                "ignore_above": 256
                                            }
                                        },
                                        "copy_to": [
                                            "named"
                                        ]
                                    }
                                }
                            },
                            "named": {
                                "type": "text",
                                "fields": {
                                    "keyword": {
                                        "type": "keyword",
                                        "ignore_above": 256
                                    }
                                }
                            },
                            "parent": {
                                "properties": {
                                    "id": {
                                        "type": "text",
                                        "fields": {
                                            "keyword": {
                                                "type": "keyword",
                                                "ignore_above": 256
                                            }
                                        }
                                    }
                                }
                            },
                            "parent_type": {
                                "type": "text",
                                "fields": {
                                    "keyword": {
                                        "type": "keyword",
                                        "ignore_above": 256
                                    }
                                }
                            },
                            "reminder_time": {
                                "type": "text",
                                "fields": {
                                    "keyword": {
                                        "type": "keyword",
                                        "ignore_above": 256
                                    }
                                }
                            },
                            "repeat_interval": {
                                "type": "text",
                                "fields": {
                                    "keyword": {
                                        "type": "keyword",
                                        "ignore_above": 256
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    init() {
        this.setEvents();
        // this.getResults({ 'page': 1, 'itemsPerPage': 10, sortBy: '' });
        this.getResults({ 'page': 1, 'itemsPerPage': 10, sortBy: '' });
    }

    setEvents() {
        this.component.$on('getResults', (params) => {
            this.getResults(params)
        });
    }

    setLabels(defs, module) {
        let sugarLabels = SUGAR.language.languages[module];

        for (const value of Object.values(defs)) {
            value.label = sugarLabels[value.label];
        }

        console.log(defs);

        return defs;
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

                this.setLabels(this.defs.columns, this.module);

                this.component.$data.totalResults = data.total;
                this.component.$data.results = data.results;
            }.bind(this)
        });
    }

    setParams(params) {
        let viewToolsParams = {};
        viewToolsParams.page = params.page;
        viewToolsParams.itemsPerPage = params.itemsPerPage;
        if (params.sortBy[0]) {
            let sortBy = this.columnNameInMappings(params.sortBy[0]);
            viewToolsParams.sortBy = sortBy;
            viewToolsParams.sortOrder = params.sortDesc[0] ? 'desc' : 'asc';
        }

        return viewToolsParams;
    }

    // getResults(params) {
    //     this.component.$data.loading = true;
    //     let urlWithParams = this.addParamsToUrl(params);

    //     console.log(urlWithParams);

    //     fetch(urlWithParams)
    //         .then((response) => response.json())
    //         .then((data) => {
    //             this.component.$data.totalResults = data.total;
    //             this.component.$data.results = data.results;
    //         });
    //     this.component.$data.loading = false;
    // }

    // addParamsToUrl(params) {
    //     let urlWithParams = new URL(this.url);
    //     urlWithParams.searchParams.append('page', params.page);
    //     urlWithParams.searchParams.append('perPage', params.itemsPerPage);

    //     if (params.sortBy[0]) {
    //         let sortBy = this.columnNameInMappings(params.sortBy[0]);
    //         urlWithParams.searchParams.append('sortBy', sortBy);
    //         urlWithParams.searchParams.append('sortOrder', params.sortDesc[0] ? 'desc' : 'asc');
    //     }

    //     return urlWithParams.toString();
    // }

    columnNameInMappings(column) {
        if (Object.values(this.mappings)[0].mappings.Calls.properties.hasOwnProperty(column)) return column;

        if (column == 'name') return 'named';
        
        if (column == 'date_entered') return 'meta.created.date';
        if (column == 'created_by') return 'meta.created.user_id';
        if (column == 'date_modified') return 'meta.modified.date';
        if (column == 'modified_user_id') return 'meta.modified.user_id';
        if (column == 'assigned_user_id') return 'meta.assigned.user_id';
        if (column == 'modified_by_name') return 'meta.modified.user_name';
        if (column == 'created_by_name') return 'meta.created.user_name';
        if (column == 'assigned_user_name') return 'meta.assigned.user_name';

        else {
            console.log('wartosc niestandardowa');
            return '';
        }
    }
}

window.ESList = ESList;
