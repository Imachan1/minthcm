class News {

    news_tpl = 'index.php?module=News&action=getNewsTpl';
    constructor(type, record_id, name, content_of_announcement, button_text) {
        this.news_type = type;
        this.record_id = record_id;
        this.name = name;
        this.content_of_announcement = content_of_announcement;
        this.button_text = button_text;
    }

    getNewsBody() {
        var body = localStorage.getItem("news_template");
        return body({
            type: this.news_type,
            record_id: this.record_id,
            name: this.name,
            content_of_announcement: this.content_of_announcement,
            button_text: this.button_text,
        });
    }

    loadTpl() {
        var template = '';
        $.ajax({
            url: this.tpl,
            success: function (result) {
                template = result;
            },
            async: false,
        }.bind(this));
        localStorage.setItem("news_template", _.template(template));
    }
}
