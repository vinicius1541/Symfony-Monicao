/**
 * Add all your custom js here, and on your twig file you just use the {% block custom_js %} {% endblock %} statement
 * and inside this block you add the <script> of this file(in case you want to create a new custom js file then just put the path of your file)
 *
 * Example:
 *          {% block custom_js %}
 *              <script src="{{ asset('js/custom.js')}}"></script>
 *          {% endblock custom_js %}
 */
$(document).ready(function () {
    /**
     * Profile's checkbox returns nothing if it's false, so this function is going to fill the checkbox's value
     * and set the attribute "disabled" for the hidden input
     */
    function updateProfileContent() {
        $(':checkbox[id^="input-profile[]"]').each(function (index) {
            if( this.checked === false) {
                $(':input[id^="input-profile-hidden"]')[index].disabled = true;
            } else {
                ($(':input[id^="input-profile-hidden"]')[index]).disabled = false;
            }
            $(':checkbox[id^="input-profile"]')[index].value = this.checked ? "true" : "false";
        })
    }
    updateProfileContent();
    /**
     * for every change on the profile-check class(which is the checkbox input), the updateProfileContent is going to be
     * called. Upwards it is called again because when the page is first loaded, there is no change on the checkbox inputs
     */
    $(document).on("change", ".profile-check" , function() {
        updateProfileContent();
    });
});