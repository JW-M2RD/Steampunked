function Game(sel) {
    this.update(sel);
}


Game.prototype.update = function(sel) {
    var that = this;
    var form = $(sel);

    // onclick listener for the form
    // sets button "clicked" attribute upon form submission
    $("form input[type=submit]").click(function() {
        $("form input[type=submit]").removeAttr("clicked");
        $(this).attr("clicked", "true");
    });

    form.submit(function(event) {

        event.preventDefault();

        console.log("Form submitted");

        // grab the button that was pressed
        var button = $("input[type=submit][clicked=true]");
        // The name attribute of the button that was pressed
        var name = button.attr('name');
        console.log("button name: " + name);

        // get the selected pipe index (one will always be selected even if user isn't manipulating pipes)
        var pipeIndex = $("input[name=pipe]:checked").val()

        var data = {};

        switch(name) {
            case "rotate":
                data = {rotate:"rotate",pipe:pipeIndex};
                console.log("rotating index: " + pipeIndex);
                break;
            case "insert":
                var insertLoc = button.attr("value");
                data = {insert:insertLoc,pipe:pipeIndex};
                break;
            case "discard":
                data = {discard:"discard",pipe:pipeIndex};
                break;
            case "open-valve":
                data = {"open-valve":"open-valve"};
                break;
            case "give-up":
                data = {"give-up":"give-up"};
                break;
            case "replay":
                data = {"replay":"replay"};
                break;
            case "new-game":
                data = {"new-game":"new-game"}; // needs to be posted to the controller so that the session can get unset
                break;
            default:
                // default action

        }
        $.ajax({
            url: "game-post.php",
            data: data,
            method: "POST",
            success: function(data) {
                console.log(data);
                var msg = JSON.parse(data);
                console.log(msg);
                $('#grid').html(msg.grid);
                $('#pipeOptions').html(msg.pipeOptions);
                $('#error').html(msg.error);
                $('#turnMessage').html(msg.turnMessage);
                $('#winnerOptions').html(msg.winnerOptions);
                that.update();
            },
            error: function(xhr, status, error) {
                console.log("Error");
            }
        });

        if(name == "new-game") {
            location.replace("../project3");
        }

    });

};