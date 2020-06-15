class News {

    news_tpl = 'themes/SuiteP/modules/News/tpls/News.tpl';

    constructor(type, record_id, name, content_of_announcement, button_text) {
        this.news_type = type;
        this.record_id = record_id;
        this.name = name;
        this.content_of_announcement = content_of_announcement;
        this.button_text = button_text;
    }

    getBoxTemplate() {
        debugger;
        return this.getNewsBody();
    }

    getNewsBody() {
        var body = this.loadTpl(this.news_tpl);
        return body({
            type: this.news_type,
            record_id: this.record_id,
            name: this.name,
            content_of_announcement: this.content_of_announcement,
            button_text: this.button_text,
        });
    }

    loadTpl(tpl) {
        var template = '';
        $.ajax({
            url: tpl,
            success: function (result) {
                template = result;
            },
            async: false,
        });
        return _.template(template);
    }
}
