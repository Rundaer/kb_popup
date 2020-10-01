
<div 
  id="myModal" 
  class="modal" 
  tabindex="-1" 
  role="dialog"
  popup-id={$popup.id}
  product-id={$popup.id_product}
>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body" style="{if $popup.background_color}background-color:{$popup.background_color}{/if}">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        {if $popup.is_image == 0}
          {$popup.text nofilter}
        {else}
          <img src="{$popup.image_show}"/>
        {/if}
      </div>
    </div>
  </div>
</div>


