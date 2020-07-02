
mintNews = {
   id: "mintNews",
   news: [],
   init: function () {
      this.loadNews();
      if (undefined === $('#' + this.id).get(0) && this.news.length > 0) {
         $('body').append('<div id="' + this.id + '">' + this.getBody(this.getHTMLFromNews()) + '</div>');
      }
      if ($('div.mintNews-boxes div.mintNews-announcement:visible').length === 0) {
         this.addCloseButton();
      }
   }.bind(this),
   getBody: function (news) {
      var body = _.template('<div class="mintNews-container"><div class="mintNews-header"><%= title %></div><div class="mintNews-boxes"><%= news %></div></div>');
      return body({
         title: viewTools.language.get('News', 'LBL_NEWS_TITLE'),
         news: news
      });
   },
   loadNews: function () {
      viewTools.api.callCustomApi({
         module: 'UsersNews',
         action: 'getNewsForUser',
         async: 'sync',
         callback: function (data) {
            if (!_.isEmpty(data)) {
               data.forEach(function (users_news) {
                  this.news.push(new News(users_news.news_type, users_news.id, users_news.name, users_news.content_of_announcement, viewTools.language.get('News', 'LBL_NEWS_' + users_news.news_type.toUpperCase() + '_BTN')));
               });
            }
         }.bind(this)
      });
   },
   getHTMLFromNews: function () {
      var html = '';
      news_object.loadTpl();
      this.news.forEach(function (news_object) {
         html += news_object.getNewsBody();
      }.bind(this));
      return html;
   },
   closeBox: function (record_id, type) {
      if (typeof type !== 'undefined') {
         viewTools.api.callCustomApi({
            module: 'UsersNews',
            action: 'createOrUpdateUsersNews',
            dataPOST: {
               record_id: record_id
            }
         });
         if (type === 'announcement' && $('div.mintNews-boxes div.mintNews-announcement:visible').length === 1) {
            this.addCloseButton();
         }
      }
      $('div.mintNews-boxes div[news-id="' + record_id + '"]').fadeOut();
      if ($('div.mintNews-boxes > div:visible').length === 1) {
         this.close();
      }
   },
   addCloseButton: function () {
      $('#mintNews .mintNews-header').append('<span class="suitepicon suitepicon-action-clear" onclick="mintNews.close();"></span>');
   },
   close: function () {
      $('#' + this.id).fadeOut();
   }.bind(this)
};
