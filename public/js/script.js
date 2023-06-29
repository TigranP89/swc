$(document).ready(function (){
  token_();

  let event = '';
  let eventName = '';
  let origin = window.location.origin;
  let eventId = '';
  let myEvent = '';
  let myEventName = '';
  let myEventId = '';
  let userID = window.userID;
  let user = '';
  let status = '';

  $.ajax({
    url: "/api/events",
    method:"GET",
    success:function (response){
      user = response.user.first_name + ' ' + response.user.last_name;
      $('#user-name').html(user);

      response.result.forEach(function (item){
        eventName = item.title;
        eventId = item.id;

        event += '<li class="nav-item">\n' +
          '        <a href="#" id="' + eventId + '" class="nav-link  my-class">\n' +
          '          <i class="nav-icon fas fa-solid fa-hashtag"></i>\n' +
          '          <p>\n' +
          eventName +
          '          </p>\n' +
          '        </a>\n' +
          '      </li>'
      })
      $('.all-events').html(event);
      participate();

      response.userResult.forEach(function (item){
        if ($.isEmptyObject(item.events)){
          console.log("Object is empty");
        } else {
          // console.log("Object is not empty");
          if (item.id == userID){
            item.events.forEach(function (data){
              myEventName = data.title;
              myEventId = data.id;

              myEvent += '<li class="nav-item ">\n' +
                '        <a href="#" id="' + myEventId + '" class="nav-link my-class">\n' +
                '          <i class="nav-icon fas fa-solid fa-hashtag"></i>\n' +
                '          <p>\n' +
                myEventName +
                '          </p>\n' +
                '        </a>\n' +
                '      </li>'
            })
          }
        }
      })
      $('.my-events').html(myEvent);
      participate();
    }
  });

  $(document).on('submit', '#loginForm', function() {
    $('#error_msg').html('');
    let login = $('#login').val();
    let password = $('#password').val();
   $.ajax({
     url: "/api/login",
     method:"POST",
     data:{
       login: login,
       password: password
     },
      success:function (res){
        if (res.token){
          window.location.href = origin + "/admin/event"
        } else {
          $('#error_msg').html(res[0]);
        }
      }
   })
    return false;
  });

  $(document).on('submit', '#registerForm', function() {
    // $('#error_msg').html('');
    let first_name = $('#first_name').val();
    let last_name = $('#last_name').val();
    let login = $('#login').val();
    let password = $('#password').val();
    let password_confirmation = $('#password_confirmation').val();
    let birth_date = $('#birth_date').val();
    $.ajax({
      url: "/api/register",
      method:"POST",
      data:{
        first_name: first_name,
        last_name: last_name,
        login: login,
        password: password,
        password_confirmation: password_confirmation,
        birth_date: birth_date,
      },
      success:function (res){
        if (res.token){
          window.location.href = origin + "/admin/event"
        } else {
          $('#register_error_msg').html(res.error);
        }
      }
    })
    return false;
  });

})

function participate()
{
  $(".my-class").on('click', function(e){
    e.preventDefault();
    let answerid = $(this).attr('id');
    let card = '';
    let date = '';
    let user = '';
    let button ='';
    let userID = window.userID;
    let found = false;

    // console.log(answerid);
    $.ajax({
      url: "/api/events/" + answerid,
      method: 'GET',
      success:function (res){
        date  = (res.eventSource.created_at).split("T");

        if($.isEmptyObject(res.eventSource.users)){
          user +='Нет учеников';
            button =  '<a href="#" class="btn btn-block btn-outline-primary btn-lg participate-btn" id="' + answerid + '">Принять участие</a>\n';
        } else {
          res.eventSource.users.forEach(function (item){
            user +='<p>'+ item.first_name +' '+ item.last_name + '</p>\n';

            if (userID == item.id){
              found = true;
              button =  '<a href="#" class="btn btn-block btn-outline-primary btn-lg cancel-event" id="' + answerid + '">Отказаться от участия</a>\n';
              return false;
            }
            if (!found){
              button =  '<a href="#" class="btn btn-block btn-outline-primary btn-lg participate-btn" id="' + answerid + '">Принять участие</a>\n';
            }
          });
        }

        card +='            <div class="card-header">\n' +
          '              <div class="card-title">\n' +
          '                sdf\n' +
          '              </div>\n' +
          '            </div>\n' +
          '            <div class="card-body pad table-responsive">\n' +
          '              <div>\n' +
          '                <h2>' + res.eventSource.title + '</h2>\n' +
          '                <p>' + res.eventSource.text + '</p>\n' +
          '                <p>' + date[0] + '</p>\n' +
          '              </div>\n' +
          '              <div>\n' +
          '                <h2>Участники</h2>\n' +
                              user +
          '              </div>\n' +
                          button +
          '            </div>'

        $('.card').html(card);
        add_to_event();
        cancel_event();
      }
    })
  })
}

function add_to_event()
{
  $('.participate-btn').on('click', function (e){
    e.preventDefault();
    var event_id = $(this).attr('id');

    $.ajax({
      type: "POST",
      url: "/api/participate/",
      data:{event_id:event_id},
      success:function (res){
        console.log(res)
      }
    });
  })
}
function cancel_event()
{
  $('.cancel-event').on('click', function (e){
    e.preventDefault();
    var event_id = event_id = $(this).attr('id');
    console.log(event_id);
    $.ajax({
      type: "POST",
      url: "/api/cancel_event",
      data:{event_id:event_id},
      success:function (res){
        console.log(res)
      }
    });
  })
}