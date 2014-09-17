// Initialize your app
var myApp = new Framework7();

var debug = true;

// Export selectors engine
var $$ = Dom7;

// Add view
var mainView = myApp.addView('.view-main', {
    // Because we use fixed-through navbar we can enable dynamic navbar
    dynamicNavbar: true
});

// Callbacks to run specific code for specific pages, for example for About page:
myApp.onPageInit('about', function (page) {
    // run createContentPage func after link was clicked
    $$('.create-page').on('click', function () {
    });
});

var EVENTS_OBJ = {};
var SERVICES_OBJ = {};
var OFFICES_OBJ = {};

var eventListTmpl = $$('script#event_list_tmpl').html();
var compiledEventListTmpl = Template7.compile(eventListTmpl);

/* Compile templates once on app load/init
var event_list = $('script#event_list_tmpl').html();
var compiledSearchTemplate = Template7.compile(searchTemplate);
 
var listTemplate = $('script#list-template').html();
var compiledListTemplate = Template7.compile(listTemplate);
 
// That is all, now and further just execute compiled templates with required context
myApp.onPageInit('search', function (page) {
    // Just execute compiled search template with required content:
    var html = compiledSearchTemplate({datatasdadada data});
 
    // Do something with html...
}); */


/*
 =====================================================
 ***********  Ajax error Function handler  ************
 ======================================================
 */
var ajaxErrHndlr = function(jqXHR) {
  if (jqXHR.status === 0) {
    xCodeLog('No Response\n Verify Network Connectivity.');
    F7_APP.alert('No Response\n Verify Network Connectivity.');
  } else if (404 == jqXHR.status) {
    xCodeLog('Requested page not found. [404]');
    F7_APP.alert('Requested page not found. [404]');
  } else if (500 != jqXHR.status) {
    if (exception === 'parsererror') {
      xCodeLog('Requested JSON parse failed.');
      F7_APP.alert('Requested JSON parse failed.');
    } else if (exception === 'timeout') {
      xCodeLog('Time out error.');
      F7_APP.alert('Time out error.');
    } else if (exception === 'abort') {
      xCodeLog('Ajax request aborted.');
      F7_APP.alert('Ajax request aborted.');
    } else {
      xCodeLog('Uncaught Error.\n' + jqXHR.responseText);
      F7_APP.alert('Uncaught Error.\n' + jqXHR.responseText);
    }
  } else {
    xCodeLog('Internal Server Error [500].');
    F7_APP.alert('Internal Server Error [500].');
  }
};



/*
 =====================================================
 ***********  Ajax error Function handler  ************
 ======================================================
 */
var getEvents = function() {
  var qryParams = {};
  qryParams.zipCode = '30305';
  //login request
  $$.ajax({
    method: 'POST',
    async: false,
    url: 'events_json.php',
    dataType: 'application/json',
    data: qryParams,
    timeout: 5000, //10 sec timeout for ajax
    success: function(response) {
      // Convert to js object
      EVENTS_OBJ = JSON.parse(response);
    },
    error: function(jqXHR, exception) {
      ajaxErrHndlr(jqXHR);
    }
  });
}
/*
 =====================================================
 ***********  Ajax error Function handler  ************
 ======================================================
 */
var getServices = function() {
  var qryParams = {};
  qryParams.zipCode = '30305';
  //login request
  $$.ajax({
    method: 'POST',
    url: 'resources_json.php',
    dataType: 'application/json',
    data: qryParams,
    timeout: 5000, //10 sec timeout for ajax
    success: function(response) {
      // Convert to js object
      var jsonObj =  JSON.parse(response);
      console.log(jsonObj);
    },
    error: function(jqXHR, exception) {
      ajaxErrHndlr(jqXHR);
    }
  });
}
/*
 =====================================================
 ***********  Ajax error Function handler  ************
 ======================================================
 */
var getOffices = function() {
  var qryParams = {};
  qryParams.zipCode = '30305';
  //login request
  $$.ajax({
    method: 'POST',
    url: 'offices_json.php',
    dataType: 'application/json',
    data: qryParams,
    timeout: 5000, //10 sec timeout for ajax
    success: function(response) {
      // Convert to js object
      var jsonObj =  JSON.parse(response);
      console.log(jsonObj);
    },
    error: function(jqXHR, exception) {
      ajaxErrHndlr(jqXHR);
    }
  });
}
/*
 =====================================================
 ***********  Ajax error Function handler  ************
 ======================================================
 */
var app_into = function() {
  function start(){
    //fade in animations
    $$('#header_left').addClass('animated fadeIn')
    .animationEnd(function(){
      $$('#header_left').attr('class','');
      
    });

    $$('#header_right').addClass('animated fadeInLeft')
    .animationEnd(function(){
      $$('#header_right').attr('class','');
      $$('.page-content').addClass('animated fadeIn');
      $$('.page-content').removeClass('noshow');
    });
  };
  //mock up location query
  myApp.modal({
    title: '"American Cancer Society" Would Like to User Your Current Location',
    text: '',
    buttons: [
      {
        text: 'Don\'t Allow',
      },
      {
        text: 'OK',
        onClick: function() {
          start();
        }
      }
    ]
  });
}

var afterInit = function() {
  //scroll fade stuff
  $$('.page-content').on('scroll', function(i) {
      var selected = $$('#header_right, #header_left');
      var scrollVar = $$('.page-content').scrollTop();

      if(scrollVar < 150) {
        selected[0].style.webkitFilter = "blur(0)";
        selected[1].style.webkitFilter = "blur(0)";
        if(scrollVar < 15) {
          selected.css({'top': 0});
          selected.css({'opacity': 1});
        }
        else if(scrollVar > 15) {
          selected.css({'top': -(.2*scrollVar) + 'px' });
          selected.css({'opacity':( 170-scrollVar )/200});
        }
      }
      if(scrollVar > 150) {
       if(scrollVar > 150 &&  scrollVar < 200) {
        selected[0].style.webkitFilter = "blur("+((scrollVar-150)/50*3)+"px)";
        selected[1].style.webkitFilter = "blur("+((scrollVar-150)/50*3)+"px)";
        }
        else {
          selected[0].style.webkitFilter = "blur(3px)";
          selected[1].style.webkitFilter = "blur(3px)";
        }
      }
      
  });
};


$$(window).on('load', function() {
  getEvents();

  var first_event_obj = EVENTS_OBJ.ExecuteSearchWithFacetResult.SimpleEvents.SimpleEventDto[0];
  console.log(first_event_obj);
  var evn_name   = first_event_obj.EventName;
  var env_state  = first_event_obj.StateProvince
  var env_locName= first_event_obj.LocationName
  var evn_start  = moment(first_event_obj.StartDate);
  var evn_end    = moment(first_event_obj.EndDate);
  var even_id    = first_event_obj.EventId;
  var dateStr    = evn_start.format('ll');

  if(evn_start.diff(moment(),'days') < 30) { //more than one day
    //dateStr  = evn_start.format("MMM Do YYYY");
    //dateStr += " " + evn_start.format("h:mm a") + ' to ' + evn_start.format("h:mm a")
    evn_start.calendar();
  }
  $$('#fill_event_name').text(evn_name);

  $$('#fill_event_state').text(env_state);

  $$('#fill_event_loc').text(env_locName);
  $$('#fill_event_date').text(dateStr);
//'FROM ' + moment(StartDate).format()
//LocationName
  if(debug) {
    //cut straight to the chase
    $$('#header_left,#header_right,.page-content').removeClass('noshow');
  } else {
    //do all the pretty stuff
    app_into();
  }

  afterInit();
  myApp.onPageInit('event_list', afterInit);


  $$(window).on('click', '.event_search', function(){
    var html = compiledEventListTmpl(EVENTS_OBJ.ExecuteSearchWithFacetResult.SimpleEvents);
    mainView.loadContent(html);
  });

  $$(window).on('click', '.social_button', function() {
    var title = $$(this).attr('data-title');
    //prompt for navigation
    myApp.modal({
      title: 'Visit American Cancer Society\'s ' + title,
      text: '',
      buttons: [
        {
          text: 'Cancel',
        },
        {
          text: 'OK',
          onClick: function() {
            myApp.alert('feature not avalable in demo')
          }
        }
      ]
    });

  });



});


