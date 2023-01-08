    
function findArticle() {
    let element = document.getElementById('rechbar')
    let content = $('#rechbar').val()
    $.ajax({
        type: "post",
        url: "getImage.php",
        data: { rechbar: content },
        success: function(result) {
            if(result){
                $('#notFoundContainer').hide()    
                $('#rechImageLink').attr('href',`uploads/${result}`)
                $('#rechImage').attr("src",`uploads/${result}`)
                $('#rechImageContainer').show()    
            }
            else{
                $('#rechImageContainer').hide()    
                $('#notFoundContainer').text("Product not found")    
                $('#notFoundContainer').show()    
            }
        }
    });
  }
  window.onload = init;
  function init(){
    
  
  var input = document.getElementById("rechbar");
input.addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    event.preventDefault();
    document.getElementById("rechbarbtn").click();
  }
});
}