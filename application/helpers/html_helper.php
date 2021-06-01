<?php

if( ! function_exists('display_error')){
    function display_error($errors)
    {
        return '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
                    <h4 class="opacity-message">You are missing the following information:</h4>
                    <ul>'.$errors.'</ul>
                </div>';
    }
}
