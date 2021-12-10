// // "use strict";

// // $(function modalSemana6(){
// //   callView("Admin", "Admin", "loadDefault");
// //     // selectActividades();
// // });

// //$(function viewDefault() {
// //     'use strict';
// //       callView("Admin", "Admin", "loadDefault");
// //     // $(document).on("submit", "#form_searchEmail", function (event) {
// //     //   event.preventDefault();
// //     //   let correo = new Object();
// //     //   correo["correo"] = $("#correo").val();

// //     //   if ($("#correo").val() != "") {

// //     //   } else {
// //     //     swal({ title: "Ingrese por favor un dato ", type: "warning" });
// //     //   }
// //     // });
// // //   });
// // });
// $(document).ready(function() {
//     //======================[  Search ANSWER  BY EMAIL ]=========================//
//     $(function viewSearchEmail() {
//         $(document).on("submit", "#form_searchEmail", function(event) {
//             event.preventDefault();
//             let correo = new Object();
//             correo["correo"] = $("#correo").val();

//             if ($("#correo").val() != "") {
//                 callView("Survey", "Survey", "getSurvey", correo);
//             } else {
//                 swal({ title: "Ingrese por favor un dato ", type: "warning" });
//             }
//         });
//     });
//     //======================[  Acount  ]=========================//	
//     $(function viewAcount() {
//         $(document).on("click", "#viewAcount", function() {
//             let userId = new Object();
//             userId["userId"] = $("#userId").val();
//             callView("Admin", "Admin", "viewAcount", userId);
//         });
//     });
//     //======================[  Meeting  ]=========================//	
//     $(function viewMeeting() {
//         $(document).on("click", "#viewCreateMeeting", function() {
//             // alert('ehr')
//             callView("Meeting", "Meeting", "viewCreateMeeting");
//         });
//     });
//     //======================[  Desprendible  ]=========================//	
//     $(function viewAcount() {
//         $(document).on("click", "#viewDesprendible", function() {
//             callView("Desprendible", "Desprendible", "viewDesprendible", userId);
//         });
//     });
//     //=============================[  PRODUCT  ]=========================//	
//     $(function viewCreateProduct() {
//         $(document).on("click", "#viewProduct", function() {
//             callView("Product", "Product", "viewProduct");
//         });
//     });
//     // -------------------------------------------------------------//
//     /****SHOW MODAL Search PRODUCT****/
//     $(document).on("click", "#Search", function() {
//         $.ajax({
//             url: "../../app/lib/ajax.php",
//             method: "post",
//             data: {
//                 module: "Product",
//                 controller: "Product",
//                 nameFunction: "modalSearchProduct",
//             }
//         }).done((res) => {
//             $("#modalSearchProduct .modal-body").html(res);
//             $("#modalSearchProduct").modal({ backdrop: "static", keyboard: false });
//         });
//     });




// });