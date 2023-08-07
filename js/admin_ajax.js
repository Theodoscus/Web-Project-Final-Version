$(document).ready(function(){
    
    $('#category_select').change(function(){

        var categoryid=$(this).val();
        console.log(categoryid);

        //Empty subcategory dropdown

        $('#subcategory_select').find('option').not(':first').remove();

        //AJAX request
        $.ajax({
            url:  '../components/backend_script.php',
            type: 'post',
            data: {request: 1, categoryid: categoryid},
            dataType: 'json',
            success: function(response){
                
                var len = response.length;

                for( var i = 0; i<len; i++){
                    
                    var subcategory_id = response[i]['subcategory_id'];
                    var subcategory_name = response[i]['subcategory_name'];
                    $("#subcategory_select").append("<option value='"+subcategory_id+"'>"+subcategory_name+"</option>");
                    
                    }       
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                 }
                        
            });
        });

    

    });
