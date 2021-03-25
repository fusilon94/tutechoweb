
var all_cupon_codes = [];//aca se almacenan todos los codigos de cupones
var QR_db_insert = []; // aca se almacena el merge de QRs y Sponsors_names

$(document).ready(function(){
 jQuery(function($){

      var QR_containers_number = $('.popup_promo_qr').length;
      var p = 1;


      while (p <= QR_containers_number) {

        var result           = '';
        var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        var charactersLength = characters.length;
        for ( var i = 0; i < 35; i++ ) {
           result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }

        var QR_link = "http://localhost:81/TuTechoweb/check_qr.php" + "#" + result;

        all_cupon_codes.push(result);

        new QArt({
          value: QR_link,
          imagePath: '../../objetos/sponsor_consola.svg',
          filter: 'threshold',
          size: 195,
          version: 10,
          fillType: 'scale_to_fit'
        }).make(document.getElementById('QR_barcode_' + p));

        p++;
      };

      for ( var i = 0; i < all_cupon_codes.length; i++ ) {//permite crear el array que contiene los QR y los Sponsors Names juntos
        QR_db_insert.push( [ all_cupon_codes[i], sponsors_names[i], cupones_vencimientos[i] ] );
      };



      $.ajax({
        url: 'process_request_almacenar_QRcupones.php',
        type: 'POST',
        data: { qr_list_sent: QR_db_insert}
      }).done(function(data){
        // alert(data);
      });

 });
});
