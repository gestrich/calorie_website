function fillAutoComplete() {

        var data;
        var availableTags=new Array();
        var calorieAmounts = new Array();
        $.get("food_items.php", 
           function(data){
                var calorieObj = obj = JSON.parse(data);
                jQuery.each( calorieObj, function(index, value) {
                availableTags[index] = value.Food;
                calorieAmounts[value.Food] = value.Calories;
                });
        });

        $( "#food_name_field" ).autocomplete({
            source: availableTags,

                select: function(e, ui){
                        var numcalories = document.getElementById("numcalories");
                        numcalories.value = calorieAmounts[ui.item.value];
                }


        });

};
