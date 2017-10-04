
function getBoardId(){
      $.ajax({
          cache: false,
          url: "https://trello.com/b/qtjdYX6F.json",
          contentType: "application/json",
          type: "GET",
          processData: false
      }).done(function(data) {
          console.log( "Sample of data:", data );
      });
      // fetch("https://trello.com/b/qtjdYX6F.json")
      //  .then(response => response.json())
      //  .then(json => console.log(json));
      //  return;
}

setTimeout(function () {
    getBoardId();
    console.log('loaded board id');
}, 10);
