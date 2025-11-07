<script id="editDeleteScript" type="text/x-jsrender">
    <?php if (auth()->user()->hasPermissionTo('update_product')): ?>
        <a href="{{:url}}" class="btn btn-warning btn-sm">{{:edit}}</a>
    <?php endif; ?>
    <?php if (auth()->user()->hasPermissionTo('delete_product')): ?>
        <button class="btn btn-danger btn-sm" id="deleteProduct" data-id="{{:id}}">{{:delete}}</button>
    <?php endif; ?>
</script>
