import './bootstrap';
import 'bootstrap/dist/js/bootstrap.min.js';

import 'datatables.net-dt/css/dataTables.dataTables.css';
import DataTable from 'datatables.net';
window.DataTable  =  DataTable;

import 'jsrender';


$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //cart
    function count() {
        // let totalCount = 0;
        // $('.quantity-cart').each(function () {
        //     let qty = parseInt($(this).text());
        //     totalCount += qty;
        // });
        // $('.count').text(totalCount);
    }
    function updateTotal() {
        // let totalPrice = 0;
        //         // $('.quantity-cart').each(function () {
        //         //     let quantity = parseFloat($(this).text());
        //         //     console.log(quantity)
        //         //     let price = $(this).parents('.row').find('.cart-price').text();
        //         //     console.log(price)
        //         //     let total = quantity * price;
        //         //     totalPrice += total;
        //         // });
        //         //
        //         $('.total').text(null)
        //         $('.doller').text(null)
        //         // $('.checkout-total').text(null)
        //         $('.subtotal').text(null)
                // $('.checkout-subtotal').text(null)


    }

    // updateTotal();
    count();
    $(document).on('click', '.close-product', function () {
        let deleteId = $(this).data('variant');
        let cartId = $(this).data('variant');
        let row = $(this).closest('.cart-' + cartId);
        updateTotal();
        $.ajax({
            url: route('delete.cart'),
            type: "GET",
            data: {
                delete_id: deleteId
            },
            success: function (response) {
                if (response.status == 'success') {
                    row.remove();
                    $('.count').text(response.count)
                    $('.subtotal').text(response.subtotal)
                    $('.total').text(response.total)
                    $('.checkout-subtotal').text(response.subtotal)
                    $('.checkout-total').text(response.total)
                    // count();

                }
            },
        });
    });


    //menu
        function variantSize(){
            $('.variant-button').each(function() {
                let size = $(this);
                let variantId = size.data('id');
                let productId = size.data('product-id');
                let price = size.data('price');
                let sku = size.data('sku');
                let variantName = size.text();
                size.parents('.card').find('.price').text(price);
                size.parents('.card').find('.price').attr('data-sku', sku);
                size.parents('.card').find('.price').attr('data-variant-title', variantName);
                size.parents('.card').find('.price').attr('data-variant', variantId);
                size.parents('.card').find('.price').attr('data-product-id', productId);

                let quantityProduct = $('.product-' + productId).find('.increment-decrement-add');
                quantityProduct.attr('data-variant' , variantId)

                if(size.data("exitstVariant")){
                    size.parents('.card').find('.add-to-cart').hide();
                    size.parents('.card').find('.increment-decrement-add').show();
                }
                else{
                    size.parents('.card').find('.add-to-cart').show();
                    size.parents('.card').find('.increment-decrement-add').hide();
                }
            });
        }
        variantSize();
        $(document).on('click', '.variant-button', function () {
            let price = $(this).data('price');
            let productId = $(this).data('product-id');
            let sku = $(this).data('sku');
            let variant = $(this).data('id');
            let variantName = $(this).data('variant-title');
            let product = $('.product-' + productId).find('.price');
            product.data('sku', sku);
            product.text(price)
            product.data('variant', variant);
            product.data('variant-title', variantName);


            $.ajax({
                url: route('cart.check') ,
                type: "GET",
                data: {
                    'variant_id' :  variant,
                },
                success: function (response) {
                    if(response.status == 'success'){
                        product.parents('.card').find('.add-to-cart').addClass('d-none');
                        product.parents('.card').find('.increment-decrement-add').removeClass('d-none');
                        product.parents('.card').find('.quantity-cart').text(response.quantity)
                    }
                    else if(response.status == 'error'){
                        product.parents('.card').find('.add-to-cart').removeClass('d-none');
                        product.parents('.card').find('.increment-decrement-add').addClass('d-none');
                    }
                },
            });

        });

        $(document).on('click' , '.add-to-cart' , function (){
            let product = $(this).parents('.card');
            let price = product.find('.price').text();
            let productId = product.data('id');
            let variantId = product.find('.price').data('variant');
            let title = product.find('.card-title').text();
            let image = product.data('url');
            let size = product.find('.price').data('sku');
            console.log(size)
            $.ajax({
                url: route('add.cart') ,
                type: "GET",
                data: {
                    price : price,
                    product_id : productId,
                    variant_id : variantId,
                    quantity : 1,
                    title : title,
                    size : size,
                    image : image,
                },
                success: function (response) {
                    if(response.quantity){
                        $('.cart-' + response.cartId).find('.quantity-cart').text(response.quantity)
                    }
                    else{
                        $('#allCartData').append(response.html)
                    }
                    $('.count').text(response.count)
                    $('.subtotal').text(response.subtotal)
                    $('.total').text(response.total)
                    $('.credit').text(response.credit)
                    product.find('.add-to-cart').hide();
                    product.find('.increment-decrement-add').removeClass('d-none');
                    product.find('.increment-decrement-add').show();
                    product.find('.quantity-cart').text(1)
                    count();
                },
            });
        });

        $(document).on('click' , '.increment' , function (){
            let productId = $(this).parents('.d-flex').data('product');
            let variantId = $(this).parents('.d-flex').data('variant');
            let quantity = $(this).parents('.d-flex').find('.quantity-cart');
            let cartQuantity = quantity.text();
            let checkoutQuantity = $(this).parents('.d-flex').find('.quantity-checkout').text();
            let credit = $('.credit').text();
            let newQuantity = 0;

            if(cartQuantity){
                newQuantity = parseInt(cartQuantity);
            }
            else{
                newQuantity = parseInt(checkoutQuantity);
            }

            updateTotal();
            $.ajax({
                url: route('update.quantity'),
                type: "GET",
                data: {
                    product_id: productId,
                    variant_id: variantId,
                    quantity: newQuantity + 1,
                    credit : credit,
                },
                success: function (response) {
                    $('.cart-quantity-'+productId+'-'+variantId).text(response.quantity);
                    $('.checkout-quantity-'+productId+'-'+variantId).text(response.quantity);

                    quantity.text(response.quantity);
                    $('.count').text(response.count)
                    $('.subtotal').text(response.subtotal)
                    $('.total').text(response.total)
                    $('.credit').text(response.credit)
                    $('.checkout-subtotal').text(response.subtotal)
                    $('.checkout-total').text(response.total)
                }
            });
        });
        $(document).on('click' , '.decrement' , function (){
            let productId = $(this).parents('.d-flex').data('product');
            let variantId = $(this).parents('.d-flex').data('variant');
            let quantity = $(this).parents('.d-flex').find('.quantity-cart');
            let cartQuantity = quantity.text();
            let checkoutQuantity = $(this).parents('.d-flex').find('.quantity-checkout').text();

            if(cartQuantity > 1 || checkoutQuantity > 1){
                let newQuantity = 0;
                if(cartQuantity){
                    newQuantity = parseInt(cartQuantity);
                }
                else{
                    newQuantity = parseInt(checkoutQuantity);
                }

                $.ajax({
                    url: route('update.quantity'),
                    type: "GET",
                    data: {
                        product_id: productId,
                        variant_id: variantId,
                        quantity: newQuantity - 1,
                    },
                    success: function (response) {
                        $('.cart-quantity-'+productId+'-'+variantId).text(response.quantity);
                        $('.checkout-quantity-'+productId+'-'+variantId).text(response.quantity);
                        quantity.text(response.quantity)
                        $('.count').text(response.count)
                        $('.subtotal').text(response.subtotal)
                        $('.total').text(response.total)
                        $('.checkout-subtotal').text(response.subtotal)
                        $('.checkout-total').text(response.total)
                    }
                });
            }
        });


        $(document).on('click' , '.checkoutBtn' , function (){
            window.location.href = route('checkout.show');
        });


    //chat in dashboard

    let refreshInterval;
    $('#messageSend').hide();

    $(document).on('keyup', '#searchChat' ,  function () {
        let query = $(this).val();
        $.ajax({
            url: route('search.user'),
            method: "GET",
            data: {
                search: query
            },
            success: function (response) {
                $('.search-data').html(response.html);
                $('.message-history').hide();
            },
            error: function (response){
                $('.search-data').html(response.html);
            }
        });
    });


    $(document).on('click' , '#selectUser' , function (){
        let id = $(this).data('id');
        $.ajax({
            url: route('message.store'),
            method: "GET",
            dataType : 'json',
            data: {
                user_id: id,
            },
            success: function (response) {
                $('#messageSend').show();
                $('.message-header').find('.message-header-user').text(response.messageUser)
                $('.message-header').find('.message-header-user').data('id' , response.messageId)
                $('.message-header').addClass("border-bottom")
                $('.search-data').text(null);
                $('.message-history').show();
                $('#searchChat').val(null);
            },
        });
    })

    $(document).on('click' , '.user-message-data' , function (){
        $('#messageSend').show();
        let name = $(this).data('name');
        let messageId = $(this).data('message-id');
        $('.message-header').find('.message-header-user').text(name)
        $('.message-header').find('.message-header-user').data('id' , messageId);

        function getMessages(){
            $.ajax({
                url:route('chat.get.messages'),
                method: "GET",
                data: {
                    message_id: messageId
                },
                success: function (response) {
                    $('.message-user').html(response.reply)
                    $('.message-header').addClass("border-bottom")
                }
            });
        }
        getMessages();
        if(refreshInterval){
            clearInterval(refreshInterval);
        }
        refreshInterval = setInterval(getMessages, 5000);
    })

    $(document).on('click', '.send-message' , function (){
        let messageId = $('.message-header').find('.message-header-user').data('id');
        console.log( $('.message-header').find('.message-header-user'))
        let message = $(this).parents('#messageSend').find('.message-text').val();

        $.ajax({
            url: route('chat.store'),
            method: "post",
            data: {
                message_id : messageId,
                message : message,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (response) {
                let align;
                if(response.message_user.admin_id == response.auth_id || response.send_by_admin == 1){
                    align = 'justify-content-end';
                }
                else {
                    align = 'justify-content-start';
                }
                let reply = `
                                <div class="d-flex ${align}">
                                    <div class="message message-${response.message_reply_id}" data-message-id="${response.message_user.id}" data-message-reply-id="${response.message_reply_id}" data-message="${response.message}" data-send-by-admin="${response.send_by_admin}">
                                        <small>${response.created_at}</small>
                                        <div class="d-flex">
                                            <p class="one-message">${response.message}</p>
                                            <div class="dropdown" >
                                                <button class="dropdown-toggle"  type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                       <svg  xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                                                            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                                                       </svg>
                                                </button>
                                                <ul class="dropdown-menu" style="top: 3px; left: -98px;">
                                                       <li class="mb-2">
                                                          <input type="hidden" name="edit_message" value="${response.message_reply_id}">
                                                          <input type="hidden" name="message_id" value="${response.message_user.id}">
                                                          <span  class="edit-btn dropdown-item m-0" data-message = "${response.message}" data-id="${response.message_reply_id}"> Edit </span>
                                                      </li>
                                                     <li>
                                                        <span class="delete-btn dropdown-item" data-id="${response.message_reply_id}">Delete</span>
                                                     </li>
                                                </ul>
                                           </div>
                                        </div>
                                    </div>
                                </div>
                                `;
                $('.message-user').append(reply)
                $('.message-text').val('');
            },
        });
    });

    $(document).on('click' , '.edit-btn' , function (){
        let message = $(this).data('message');
        let messageId = $(this).data('id');
        $('#messageSend').find('.btn').removeClass('send-message');
        $('#messageSend').find('.btn').text('Update');
        $('#messageSend').find('.btn').addClass('update-message')
        $('#messageSend').find('.message-text').val(message)
        $('#messageSend').find('.update-message').data('id' ,messageId )
    });

    $(document).on('click' , '.update-message' ,function (){
        let messageId = $(this).data('id');
        let message = $(this).parents('#messageSend').find('.message-text').val();
        $.ajax({
            url: route('chat.update' , messageId),
            method: "PUT",
            data: {
                message_id: messageId,
                message : message,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (response) {
                $(`.message-${response.messageId}`).find('.one-message').text(response.message);
                $(`.message-${response.messageId}`).data('message' , response.message);
                $('.message-text').val('');
            }
        });
    })

    $(document).on('click' , '.delete-btn' , function (){
        let deleteId = $(this).data('id');
        $.ajax({
            url: route('chat.destroy' , deleteId),
            method: "DELETE",
            data: {
                delete_id: deleteId,
            },
            success: function (response) {
                $(`.message-${deleteId}`).remove();
            }
        });
    });


    //permission in index

    let table = new DataTable('#PermissionController', {
        deferRender: true,
        scroller: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: route('permission.index'),
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            {
                data: function (row) {
                    let url = route('permission.edit' , row.id );
                    let data = [{
                        'id': row.id,
                        'url': url,
                        'edit': 'Edit',
                        'delete': 'Delete'
                    }];
                    let template = $.templates("#editDeleteScript");
                    return template.render(data);
                },
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ]
    });


    $(document).on('click', '#deletePermission', function () {
        let permissionId = $(this).data('id');

        $.ajax({
            url: route('permission.destroy' , permissionId),
            type: "DELETE",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function () {
                table.ajax.reload(null, false);
            },
        });
    });

    //product in create

    $(document).on('click' , '.product-create-submit-btn' , function (e){
        e.preventDefault()
        let myForm = $('#productCreateForm')[0];
        let formData = new FormData(myForm);
        $.ajax({
            url: route('product.store'),
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                window.location.href = route('product.index')
            },
            error: function (response) {
                let errors = response.responseJSON.errors;

                if (errors.title) {
                    $('.product-title-error').text(errors.title[0]);
                }

                if (errors.description) {
                    $('.product-description-error').text(errors.description[0]);
                }

                for(let i=0; i< $('.single-variant-title').length; i++){
                    if (errors['variant_title.'+ i]) {
                        $($('.single-variant-title')[i]).siblings('.variant-title-error').text(errors['variant_title.' + i][0]);
                    }
                }
                for(let i=0; i< $('.single-variant-price').length; i++){
                    if (errors['price.'+ i]) {
                        $($('.single-variant-price')[i]).siblings('.variant-price-error').text(errors['price.' + i][0]);
                    }
                }
                for(let i=0; i< $('.single-variant-sku').length; i++){
                    if (errors['sku.'+ i]) {
                        $($('.single-variant-sku')[i]).siblings('.variant-sku-error').text(errors['sku.' + i][0]);
                    }
                }
                for(let i=0; i< $('.single-variant-wholesaler-price').length; i++){
                    if (errors['wholesaler_price.'+ i]) {
                        $($('.single-variant-wholesaler-price')[i]).siblings('.variant-wholesaler-price-error').text(errors['wholesaler_price.' + i][0]);
                    }
                }
            },
        });

    });


    //product in edit and create

    $('#customFile').on('change', function(e) {
        const files = e.target.files;
        const preview = $('#imagePreview');
        preview.innerHTML = '';

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            if (file.type.startsWith('image')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '100px';
                    img.style.padding = '.25rem';
                    img.style.backgroundColor = '#fff';
                    img.style.border = "1px solid #dee2e6";
                    img.style.borderRadius = "0.375rem";
                    img.style.height = "auto";
                    img.style.marginTop = '0.5rem';
                    preview.append(img);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    $(document).on('click' , '#addVariant' , function (){
        let row =`
                    <div class="row pt-5 single-variant">
                        <div class="col">
                            <label class="form-label fw-bold" for="title">Title</label>
                            <input type="text" class="form-control single-variant-title"  name="variant_title[]">
                            <span class="variant-title-error text-danger"></span>
                        </div>
                        <div class="col">
                            <label class="form-label fw-bold" for="price">Price</label>
                            <input type="text" class="form-control single-variant-price" name="price[]">
                            <span class="variant-price-error text-danger"></span>
                        </div>
                        <div class="col">
                            <label class="form-label fw-bold" for="sku" >Sku</label>
                            <input type="text" class="form-control single-variant-sku" name="sku[]">
                            <span class="variant-sku-error text-danger"></span>
                        </div>
                        <div class="col">
                            <label class="form-label fw-bold" for="wholesalerPrice">Wholesaler Price</label>
                            <input type="text" class="form-control single-variant-wholesaler-price" name="wholesaler_price[]">
                            <span class="variant-wholesaler-price-error text-danger"></span>
                        </div>
                         <div class="col d-flex justify-content-center align-items-center">
                             <input type="button" class="btn btn-danger delete-variant" value="Delete" style="width: 114px; height: 44px;">
                         </div>
                    </div>
                `;
        $('.variants').append(row)
    })

    $(document).on('click' , '.delete-variant' , function (){
        if($('.single-variant').length > 1){
            $(this).parents('.single-variant').remove();
        }
    });

    //product in edit

    $(document).on('click' , '.edit-product-submit-button' , function (e){
        e.preventDefault()
        let myForm = $('#editProductForm')[0];
        let formData = new FormData(myForm);
        let productId = $('.edit-product-id').val();
        $.ajax({
            url: route('product.update' , productId),
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                window.location.href = route('product.index')
            },
            error: function (response) {
                let errors = response.responseJSON.errors;

                if (errors.title) {
                    $('.product-title-error').text(errors.title[0]);
                }

                if (errors.description) {
                    $('.product-description-error').text(errors.description[0]);
                }

                for(let i=0; i< $('.single-variant-title').length; i++){
                    if (errors['variant_title.'+ i]) {
                        $($('.single-variant-title')[i]).siblings('.variant-title-error').text(errors['variant_title.' + i][0]);
                    }
                }
                for(let i=0; i< $('.single-variant-price').length; i++){
                    if (errors['price.'+ i]) {
                        $($('.single-variant-price')[i]).siblings('.variant-price-error').text(errors['price.' + i][0]);
                    }
                }
                for(let i=0; i< $('.single-variant-sku').length; i++){
                    if (errors['sku.'+ i]) {
                        $($('.single-variant-sku')[i]).siblings('.variant-sku-error').text(errors['sku.' + i][0]);
                    }
                }
                for(let i=0; i< $('.single-variant-wholesaler-price').length; i++){
                    if (errors['wholesaler_price.'+ i]) {
                        $($('.single-variant-wholesaler-price')[i]).siblings('.variant-wholesaler-price-error').text(errors['wholesaler_price.' + i][0]);
                    }
                }
            },
        });

    });


    $(document).on('click' , '.delete-edit-variant' , function (){
        let editId = $(this).parents('.single-variant').find('.edit-id').val();
        $.ajax({
            url: route('delete.variant'),
            type: "GET",
            data: {
                delete_id : editId
            },
            success: function () {
            },
        });
    })

    //product in index

    let tableProduct = new DataTable('#productContainer', {
        deferRender: true,
        scroller: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: route('product.index'),
        },
        columnDefs: [
            {
                targets: [1,2,3,4],
                searchable: true,
            }
        ],
        columns: [
            { data: 'id', name: 'id' },
            { data: 'title', name: 'title' },
            { data: 'description', name: 'description' },
            { data: 'status', name: 'status' , type: 'string' },
            {
                data: function (row) {
                    let url = route('product.edit' , row.id );
                    let data = [{
                        'id': row.id,
                        'url': url,
                        'edit': 'Edit',
                        'delete': 'Delete'
                    }];
                    let template = $.templates("#editDeleteScript");
                    return template.render(data);
                },
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ]
    });


    $(document).on('click', '#deleteProduct', function () {
        let userId = $(this).data('id');

        $.ajax({
            url: route('product.destroy' , userId),
            type: "DELETE",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function () {
                tableProduct.ajax.reload(null, false);
            },
        });
    });

    //roles in index

    let tableRole = new DataTable('#RolesContainer', {
        deferRender: true,
        scroller: false,
        processing: true,
        serverSide: true,
        ajax: {
            url:route('role.index'),
        },
        columns: [
            { data: 'id', name: 'id' },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: function (row) {
                    let url = route('role.edit' , row.id );
                    let data = [{
                        'id': row.id,
                        'url': url,
                        'edit': 'Edit',
                        'delete': 'Delete'
                    }];
                    let template = $.templates("#editDeleteScript");
                    return template.render(data);
                },
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ]
    });


    $(document).on('click', '#deleteRole', function () {
        let roleId = $(this).data('id');

        $.ajax({
            url: route('role.destroy' , roleId),
            type: "DELETE",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function () {
                tableRole.ajax.reload(null, false);
            },
        });
    });


    //user in index
    let tableUser = new DataTable('#userDemoContainer', {
        deferRender: true,
        scroller: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: route('user.index'),
        },
        columnDefs: [
            {
                targets: [1,2,3,4,5,6],
                searchable: true,
            }
        ],
        columns: [
            { data: 'id', name: 'id' },
            { data: 'first_name',  name: 'first name' },
            { data: 'last_name', name: 'last name' },
            { data: 'email', name: 'email' },
            { data: 'hobbies', name: 'hobbies' },
            { data: 'phone_number', name: 'phone number' , type: 'string' },
            { data: 'gender', name: 'gender' },
            {
                data: function (row) {
                    let url = route('user.edit' , row.id );
                    let data = [{
                        'id': row.id,
                        'url': url,
                        'edit': 'Edit',
                        'delete': 'Delete'
                    }];
                    let template = $.templates("#editDeleteScript");
                    return template.render(data);
                },
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ]
    });


    $(document).on('click', '#deleteUsers', function () {
        let userId = $(this).data('id');

        $.ajax({
            url: route('user.destroy' , userId),
            type: "DELETE",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function () {
                tableUser.ajax.reload(null, false);
            },
        });
    });

    //user in edit
    $(document).on('click' , '.add-credit' , function (){
        let creditReason =  $('#reason').val();
        let creditAmount = $('.credit-amount').val();

        $.ajax({
            url: route('credit.add'),
            method: "GET",
            data: {
                'reason' : creditReason,
                'amount' : creditAmount,
            },
            success: function (response) {
                let html =  `<div class="row my-2 ">
                    <div class="col">
                        <span>${response.created_at}</span>
                    </div>
                    <div class="col">
                        <span>${response.credit_amount}</span>
                    </div>
                    <div class="col">
                        <span>${response.previous_balance}</span>
                    </div>
                    <div class="col">
                        <span>${response.new_balance}</span>
                    </div>
                    <div class="col">
                        <span>${response.reason}</span>
                    </div>
                </div>
                <hr>`;

                $('.show-new-credit').append(html);
                $('.credit-amount').val(null);

            },
            error: function (response) {
                let errors = response.responseJSON.errors;
                if (errors.credit_amount) {
                    $('.credit-amount').siblings('span').text(errors.credit_amount[0]);
                }
            },
        });
    })


    //checkout

    $('#cardButton').hide();

    $(document).on('click', '#checkoutButton', function () {
        $('#checkoutField').hide();
        $('#cardButton').show();

        let total = $('.checkout-total').text();
        let credit = $('.checkout-credit').text();
        const stripe = Stripe(window.stripePublicKey);
        let elements = null;
        $.ajax({
            url: route('cashier.payment.intent'),
            method: "GET",
            data: {
                total : total,
            },
            success: function (response) {
                console.log(response);
                elements = stripe.elements({ clientSecret: response.client_secret});
                const paymentElement = elements.create('payment');
                paymentElement.mount('#cardElement');
            },
        });

        const paymentButton = document.getElementById('cardButton');
        paymentButton.addEventListener('click',async (e) => {
            e.preventDefault();

            const {setupIntent, error} = await stripe.confirmSetup({
                elements,
                confirmParams: {
                    return_url: route('payment.success'),
                },
                redirect: "if_required",
            });

            console.log(setupIntent);
            if(error){
                console.log(error)
            }
            else{
                let myForm = $('#checkoutForm')[0];
                let formData = new FormData(myForm);
                // formData.append('total' ,total);
                // formData.append('credit' , credit);
                formData.append('paymentMethodId' , setupIntent.payment_method);
                //
                // $('.checkout-cart').each(function () {
                //     let productId = $(this).data('product');
                //     let variantId = $(this).data('variant');
                //     let quantity = $(this).find('.quantity-checkout').text();
                //     let price = $(this).find('.cart-price').text();
                //
                //     formData.append('product_id[]' , productId);
                //     formData.append('variant_id[]' , variantId);
                //     formData.append('quantity[]' , quantity);
                //     formData.append('price[]' , price);
                // })

                $.ajax({
                    url: route('order.store'),
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        window.location.href = route('order.index');
                    },
                });
            }
        });
    });

    //order in index

    let tableOrder = new DataTable('#orderContainer', {
        deferRender: true,
        scroller: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: route('order.index'),
        },
        columns: [
            {
                data: function (row){
                    return  '<a href="'+ route('order.show' , row.id)+'" data-id="'+ row.id +'" >'+ row.id +'</a>';
                },
                name: 'id'
            },
            {
               data : 'user name',
                name: 'user name'
            },
            { data: 'total', name: 'amount'  , type : 'string'},
            { data: 'refunded Amount', name: 'refunded Amount'  , type : 'string'},
            {
                data: function (row) {
                    let url = route('order.edit' , row.id );
                    let data = [{
                        'id': row.id,
                        'url': url,
                        'edit': 'Edit',
                        'delete': 'Delete'
                    }];
                    let template = $.templates("#editDeleteScript");
                    return template.render(data);
                },
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ]
    });


    //order in edit


    function updateTotalOrder() {
        let totalPrice = 0;
        $('.quantity-order').each(function () {
            let quantity = parseInt($(this).text());
            let priceText = $(this).closest('.row').find('.order-price').text();
            let price = parseInt(priceText);
            let total = quantity * price;
            totalPrice += total;
        });

        let credit = $('.order-credit').text();
        if(credit){
            totalPrice -= credit
        }

        $('.order-total').text(totalPrice);
        $('.order-subtotal').text(totalPrice);
    }
    updateTotalOrder()

    $(document).on('click' , '.order-increment' , function (){
        let productId = $(this).parents('.d-flex').data('product');
        let variantId = $(this).parents('.d-flex').data('variant');
        let orderQuantity = $(this).parents('.d-flex').find('.order-quantity-'+productId+'-'+variantId).text();
        let newQuantity = parseInt(orderQuantity) + 1;
        $('.order-quantity-'+productId+'-'+variantId).text(newQuantity);

        updateTotalOrder()
    });

    $(document).on('click' , '.order-decrement' , function (){
        let productId = $(this).parents('.d-flex').data('product');
        let variantId = $(this).parents('.d-flex').data('variant');
        let orderQuantity = $(this).parents('.d-flex').find('.order-quantity-'+productId+'-'+variantId).text();

        let newQuantity = 0;

        if(orderQuantity > 1){
            newQuantity = parseInt(orderQuantity) - 1;
        }
        $('.order-quantity-'+productId+'-'+variantId).text(newQuantity);
        updateTotalOrder()
    });

    $(document).on('click', '.close-product-order', function () {
        let orderItemId = $(this).data('id');
        let row = $(this).closest('.order-' + orderItemId);
        row.remove();
        updateTotalOrder();
    });

    $(document).on('keyup', '.search-product-edit' ,  function () {
        let search = $(this).val();
        $.ajax({
            url: route('search.order.items'),
            method: "GET",
            data: {
                search: search
            },
            success: function (response) {
                $('#searchableProduct').html(response.html);
                $('#searchableProduct').css("height", '230px');
            }
        });
    });

    $(document).on('click' , '.single-product' , function () {
        let productId = $(this).data('id');
        let variantId = $(this).data('variant');
        let imagUrl = $(this).data('url');
        let productTitle = $(this).find('.product-title-search').text();
        let variantSize = $(this).find('.variant-size-search').text();
        let price = $(this).find('.price-search').text();
        let html = '';
        html = `
            <div class="row my-3 bg-light order-item order order-product-${productId}"
             data-product="${productId}" data-variant="${variantId}">
            <div class="col">
                <img class="card-img-top rounded" src="${imagUrl}" alt="Product image"
                     style="height: 100px; width: 100px;"/>
            </div>
            <div class="col">
                <div class="row mb-2">
                    <span class="col text-muted">${productTitle}</span>
                </div>
                <div class="row">
                    <span class="col">Size : ${variantSize}</span>
                </div>
                <div class="d-flex align-items-end justify-content-around pt-2" data-product="${productId}" data-variant="${variantId}">
                    <span
                        class="fs-4 order-decrement decrement-order-${productId}-${variantId}">-</span>
                    <span
                        class="fs-5 quantity-order  order-quantity-${productId}-${variantId}">1</span>
                    <span
                        class="fs-4 order-increment increment-order-${productId}-${variantId}">+</span>
                </div>
            </div>
            <div class="col-2">
                <div class="row">
                    <button type="button" class="btn-close close-product-order "
                            aria-label="Close" data-product="${productId}" ></button>
                </div>
                <div class="pt-5 d-flex">
                    <p>$</p>
                    <p class="order-price">${price}</p>
                </div>
            </div>
        </div>
        `;

        $('#allOrderData').append(html);
        $('#searchableProduct').text('');
        $('.search-product-edit').val('')
        $('#searchableProduct').css("height", '0');
        updateTotalOrder()
    });

    $(document).on('click' , '.edit-order' , function (){
        let productId = [];
        let variantId = [];
        let quantity = [];
        let price = [];
        let editId = [];
        let orderId = $('#allOrderData').data('order');
        let total = $('.order-total').text();
        $('.order-item').each(function () {
            let productData = $(this).data('product');
            let variantData = $(this).data('variant');
            let quantityData = $(this).find('.quantity-order').text();
            let priceData = $(this).find('.order-price').text();
            let editData = $(this).find('.edit-id').val();

            productId.push(productData);
            variantId.push(variantData);
            quantity.push(quantityData);
            price.push(priceData);
            editId.push(editData)
        })

        $.ajax({
            url: route('order.update' , orderId),
            method: "PUT",
            data: {
                product_id : productId,
                variant_id : variantId,
                quantity : quantity ,
                price : price ,
                edit_id : editId,
                total : total ,
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (response) {

            },
        });

    })

    //order in show

    $(document).on('click' , '#editOrderDetails' , function (){
        let myForm = $('#editOrderForm')[0];
        let formData = new FormData(myForm);
        let editId = $('#updateField').find('.edit-id').val();
        console.log(editId)
        $.ajax({
            url: route('order.update.details', editId),
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {

            },
        });
    });

});
