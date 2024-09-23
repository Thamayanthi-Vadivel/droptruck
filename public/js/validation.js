$(document).ready(function() {
    var availableTags = [
        "Ariyalur", "Chengalpattu", "Chennai", "Coimbatore", "Cuddalore", "Dharmapuri", "Dindigul",
        "Erode", "Kallakurichi", "Kancheepuram", "Karur", "Krishnagiri", "Madurai", "Mayiladuthurai",
        "Nagapattinam", "Kanniyakumari", "Namakkal", "Perambalur", "Pudukottai", "Ramanathapuram",
        "Ranipet", "Salem", "Sivagangai", "Tenkasi", "Thanjavur", "Theni", "Thiruvallur", "Thiruvarur",
        "Thoothukudi", "Trichirappalli", "Tirunelveli", "Tirupathur", "Tiruppur", "Tiruvannamalai",
        "The Nilgiris", "Vellore", "Viluppuram", "Virudhunagar"
    ];

    $('#district').autocomplete({
        source: availableTags
    });
});