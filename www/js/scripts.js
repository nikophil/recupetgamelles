// JavaScript Document

function resize(){


}

$(window).load(function(){

  //condition initiale
  resize();

});

$(document).ready(function(){

  $(window).resize(function(){
    resize();   
  }); 
  
  var page = $('section').attr('id');
  $('#menu-'+page).addClass('active');
  
  $('.extended-link').click(function(event){
    event.preventDefault();                               
    $(location).attr('href',$(this).find('a').attr('href'));
  });  
  

});
