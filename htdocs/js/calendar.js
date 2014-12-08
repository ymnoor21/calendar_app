    /*
    * @author: Yamin Noor
    * @purpose: load all necessary javascript variables when document loads
    */
	$(function() {
        $(".ui-dialog-titlebar-close").html("x");

    	$(".calendar-day").dblclick(function() {
    		var selected_date = $(this).find(".day-number").data("date");

            $("#dlg_note_date").val(selected_date);
            $("#notedialog").dialog("open");
    	});

        $( "#notedialog" ).dialog({
            autoOpen: false,
            height: 180,
            width: 450,
            modal: true,
            close: function( event, ui ) {
                $("#dlg_note_date").val("");
                $("#dlg_note_text").val("");
                $("#dlg_note_id").val("");
                $("#dlg_delete_note").hide();
            }
        });

        $( "#notelistdialog" ).dialog({
            autoOpen: false,
            height: 300,
            width: 450,
            modal: true,
            close: function( event, ui ) {
                $("#dlg_lst").html("");
            }
        });

  	});

    /*
    * @author: Yamin Noor
    * @purpose: apply filter when year or month drop down changes
    */
    function applyFilter() {
        var year  = $("#calendar_year").val();
        var month = $("#calendar_month").val();

        document.location.href = "/calendar/view/year/" +  year + "/month/" + month;
    }

    /*
    * @author: Yamin Noor
    * @purpose: save a note via ajax
    */
    function saveNote() {
        var note_id   = $("#dlg_note_id").val();
        var note_date = $("#dlg_note_date").val();
        var note_text = $("#dlg_note_text").val();

        if(note_date != "" && note_text != "") {
            $.ajax({url: "/calendar/savenote"
                , type: "POST"
                , data: "note_date=" + note_date + "&note_text=" + encodeURIComponent(note_text) + "&note_id=" + note_id
                , success: function(response, status){
                    if(response.flag)
                    {
                        $("#" + note_date).html(response.html);
                    }
                    
                    alert(response.message);
                }
                , complete: function(xhr, status){
                    $("#notedialog").dialog("close");
                    $("#notelistdialog").dialog("close");
                }
                , dataType: "json"
            });
        }
        else {
            var msg = "";

            if(note_text == "") {
                msg = "Please enter note text";
            }
            else {
                msg = "Sorry cannot add note now";
            }

            alert(msg);
        }
    }

    function closeNote() {
        $("#notedialog").dialog("close");
    }

    function openNote(note_id) {
        if(note_id != "") {
            $("#dlg_note_id").val(note_id);

            $.ajax({url: "/calendar/getnote"
                , type: "POST"
                , data: "note_id=" + note_id
                , success: function(response, status){
                    if(response.flag)
                    {
                        $("#dlg_delete_note").show();
                        $("#dlg_note_date").val(response.note_date);
                        $("#dlg_note_text").val(response.note_text);

                        $("#notedialog").dialog("open");
                    }
                }
                , complete: function(xhr, status){
                }
                , dataType: "json"
            });
        }
    }

    function deleteNote() {
        var note_id   = $("#dlg_note_id").val();
        var note_date = $("#dlg_note_date").val();

        if(note_id != "") {
            $.ajax({url: "/calendar/deletenote"
                , type: "POST"
                , data: "note_id=" + note_id
                , success: function(response, status){
                    if(response.flag)
                    {
                        $("#" + note_date).html(response.html);
                        alert(response.message);
                    }
                }
                , complete: function(xhr, status){
                    $("#notedialog").dialog("close");
                    $("#notelistdialog").dialog("close");
                }
                , dataType: "json"
            });
        }
    }

    function openAllNotes(note_uid, note_date) {
        $("#dlg_lst").html("");

        if(note_uid != "" && note_date != "") {
            $.ajax({url: "/calendar/getnotesofuserbyday"
                , type: "POST"
                , data: "note_uid=" + note_uid + "&note_date=" + note_date
                , success: function(response, status){
                    if(response.flag)
                    {
                        var htmlTxt = "";
                        
                        htmlTxt += "<table class='table table-striped'>" +
                                   "<thead>" +
                                   "<tr>" +
                                   "<th style='text-align: left !important' width='60%'>" +
                                   "Note" +
                                   "</th>" +
                                   "<th style='text-align: center !important' width='40%'>" +
                                   "Last Modified" +
                                   "</th>" +
                                   "</tr>" +
                                   "</thead>" +
                                   "<tbody>";

                        for(var i = 0; i < response.notes.length; i++) {
                            htmlTxt += "<tr>" +
                                       "<td align='left'>" +
                                       "<a style='text-decoration: underline;' href='javascript:void(0);' onclick='openNote(" + response.notes[i].note_id + ")'>" + response.notes[i].note_text + "</a>" +
                                       "</td>" +
                                       "<td align='center'>" +
                                       response.notes[i].note_date +
                                       "</td>" +
                                       "</tr>";
                        }

                        htmlTxt += "</tbody>";
                        htmlTxt += "</table>";

                        $("#dlg_lst").html(htmlTxt);
                        $("#notelistdialog").dialog("open");
                    }
                }
                , complete: function(xhr, status){
                }
                , dataType: "json"
            });
        }
    }

