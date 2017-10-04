KB.component('TrelloMigrate', function (containerElement, options) {
    var modeMapping = {
        month: 'month',
        week: 'agendaWeek',
        day: 'agendaDay'
    };


    this.render = function () {
        var TrelloMigrate = $(containerElement);
        var mode = 'month';
        if (window.location.hash) { // Check if hash contains mode
            var hashMode = window.location.hash.substr(1);
            mode = modeMapping[hashMode] || mode;
        }

        TrelloMigrate.fullTrelloMigrate({
            locale: $("body").data("js-lang"),
            editable: true,
            eventLimit: true,
            defaultView: mode,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            eventDrop: function(event) {
                $.ajax({
                    cache: false,
                    url: options.saveUrl,
                    contentType: "application/json",
                    type: "POST",
                    processData: false,
                    data: JSON.stringify({
                        "task_id": event.id,
                        "date_due": event.start.format()
                    })
                });
            },
            viewRender: function(view) {
                // Map view.name back and update location.hash
                for (var id in modeMapping) {
                    if (modeMapping[id] === view.name) { // Found
                        window.location.hash = id;
                        break;
                    }
                }
                var url = options.checkUrl;
                var params = {
                    "start": TrelloMigrate.fullTrelloMigrate('getView').start.format(),
                    "end": TrelloMigrate.fullTrelloMigrate('getView').end.format()
                };

                for (var key in params) {
                    url += "&" + key + "=" + params[key];
                }

                $.getJSON(url, function(events) {
                    TrelloMigrate.fullTrelloMigrate('removeEvents');
                    TrelloMigrate.fullTrelloMigrate('addEventSource', events);
                    TrelloMigrate.fullTrelloMigrate('rerenderEvents');
                });


            }
        });
    };
});

KB.on('dom.ready', function () {


    function goToLink (selector) {
        if (! KB.modal.isOpen()) {
            var element = KB.find(selector);

            if (element !== null) {
                window.location = element.attr('href');
            }
        }
    }

    KB.onKey('v+c', function () {
        goToLink('a.view-TrelloMigrate');
    });

    function getBoardId(boardID){
          $.ajax({
              type: "POST",
              url: './kanboard-import-trello/import.php',
              success: function(data, response, textStatus, jqXHR) {
                 alert( "success" + data );
              }
              // data: {functionname: 'importTrelloBoard', arguments: [ $('#form-trello-board-id').val() ]},
          });

          // .done(function(data) {
          //   alert( "success" + data );
          // })
          // .fail(function(data) {
          //   alert( "error" + data );
          //   localStorage.setItem('failure objects', JSON.stringify(data) );
          //   console.log(data);
          // });
    }
    // $('.add-board').on( 'click', function(){
    //   var boardID = $('#form-trello-board-id').val();
    //   var trelloKey = $('#form-trello-api-key').val();
    //   var trelloToken = $('#form-trello-api-token').val();
    //   var kanboardToken = $('#form-kanboard-api-token').val();
    //   var kanboardEndpoint = $('#form-kanboard-api-endpoint').val();
    //
    //
    //   console.log(boardID);
    //   getBoardId(boardID);
    // });



});
