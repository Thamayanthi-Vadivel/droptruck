<div class="form-group mt-3">
    <label for="pickup_locations">Pickup Locations:</label>
    <select class="form-control" id="pickup_locations" name="pickup_locations">
        <option value="Ariyalur">Ariyalur</option>
        <option value="Chengalpattu">Chengalpattu</option>
        <option value="Chennai">Chennai</option>
        <option value="Coimbatore">Coimbatore</option>
        <option value="Cuddalore">Cuddalore</option>
        <option value="Dharmapuri">Dharmapuri</option>
        <option value="Dindigul">Dindigul</option>
        <option value="Erode">Erode</option>
        <option value="Kallakurichi">Kallakurichi</option>
        <option value="Kancheepuram">Kancheepuram</option>
        <option value="Karur">Karur</option>
        <option value="Krishnagiri">Krishnagiri</option>
        <option value="Madurai">Madurai</option>
        <option value="Mayiladuthurai">Mayiladuthurai</option>
        <option value="Nagapattinam">Nagapattinam</option>
        <option value="Kanniyakumari">Kanniyakumari</option>
        <option value="Namakkal">Namakkal</option>
        <option value="Perambalur">Perambalur</option>
        <option value="Pudukottai">Pudukottai</option>
        <option value="Ramanathapuram">Ramanathapuram</option>
        <option value="Ranipet">Ranipet</option>
        <option value="Salem">Salem</option>
        <option value="Sivagangai">Sivagangai</option>
        <option value="Tenkasi">Tenkasi</option>
        <option value="Thanjavur">Thanjavur</option>
        <option value="Theni">Theni</option>
        <option value="Thiruvallur">Thiruvallur</option>
        <option value="Thiruvarur">Thiruvarur</option>
        <option value="Thoothukudi">Thoothukudi</option>
        <option value="Trichirappalli">Trichirappalli</option>
        <option value="Tirunelveli">Tirunelveli</option>
        <option value="Tirupathur">Tirupathur</option>
        <option value="Tiruppur">Tiruppur</option>
        <option value="Tiruvannamalai">Tiruvannamalai</option>
        <option value="The Nilgiris">The Nilgiris</option>
        <option value="Vellore">Vellore</option>
        <option value="Viluppuram">Viluppuram</option>
        <option value="Virudhunagar">Virudhunagar</option>
    </select>
</div>

<div class="form-group mt-3">
    <label for="drop_locations">Drop Locations:</label>
    <select class="form-control" id="drop_locations" name="drop_locations">
        <option value="Ariyalur">Ariyalur</option>
        <option value="Chengalpattu">Chengalpattu</option>
        <option value="Chennai">Chennai</option>
        <option value="Coimbatore">Coimbatore</option>
        <option value="Cuddalore">Cuddalore</option>
        <option value="Dharmapuri">Dharmapuri</option>
        <option value="Dindigul">Dindigul</option>
        <option value="Erode">Erode</option>
        <option value="Kallakurichi">Kallakurichi</option>
        <option value="Kancheepuram">Kancheepuram</option>
        <option value="Karur">Karur</option>
        <option value="Krishnagiri">Krishnagiri</option>
        <option value="Madurai">Madurai</option>
        <option value="Mayiladuthurai">Mayiladuthurai</option>
        <option value="Nagapattinam">Nagapattinam</option>
        <option value="Kanniyakumari">Kanniyakumari</option>
        <option value="Namakkal">Namakkal</option>
        <option value="Perambalur">Perambalur</option>
        <option value="Pudukottai">Pudukottai</option>
        <option value="Ramanathapuram">Ramanathapuram</option>
        <option value="Ranipet">Ranipet</option>
        <option value="Salem">Salem</option>
        <option value="Sivagangai">Sivagangai</option>
        <option value="Tenkasi">Tenkasi</option>
        <option value="Thanjavur">Thanjavur</option>
        <option value="Theni">Theni</option>
        <option value="Thiruvallur">Thiruvallur</option>
        <option value="Thiruvarur">Thiruvarur</option>
        <option value="Thoothukudi">Thoothukudi</option>
        <option value="Trichirappalli">Trichirappalli</option>
        <option value="Tirunelveli">Tirunelveli</option>
        <option value="Tirupathur">Tirupathur</option>
        <option value="Tiruppur">Tiruppur</option>
        <option value="Tiruvannamalai">Tiruvannamalai</option>
        <option value="The Nilgiris">The Nilgiris</option>
        <option value="Vellore">Vellore</option>
        <option value="Viluppuram">Viluppuram</option>
        <option value="Virudhunagar">Virudhunagar</option>
    </select>
</div>


<div class="form-group">
    <label for="pickup_locations">Pickup Locations:</label>
    <select class="form-control" id="pickup_locations" name="pickup_locations">
        <option value="">Select Pickup Location</option>
        <option value="Ariyalur" {{ $indent->pickup_locations === 'Ariyalur' ? 'selected' : '' }}>Ariyalur</option>
        <option value="Chengalpattu" {{ $indent->pickup_locations === 'Chengalpattu' ? 'selected' : '' }}>Chengalpattu</option>
        <option value="Chennai" {{ $indent->pickup_locations === 'Chennai' ? 'selected' : '' }}>Chennai</option>
        <option value="Coimbatore" {{ $indent->pickup_locations === 'Coimbatore' ? 'selected' : '' }}>Coimbatore</option>
        <option value="Cuddalore" {{ $indent->pickup_locations === 'Cuddalore' ? 'selected' : '' }}>Cuddalore</option>
        <option value="Dharmapuri" {{ $indent->pickup_locations === 'Dharmapuri' ? 'selected' : '' }}>Dharmapuri</option>
        <option value="Dindigul" {{ $indent->pickup_locations === 'Dindigul' ? 'selected' : '' }}>Dindigul</option>
        <option value="Erode" {{ $indent->pickup_locations === 'Erode' ? 'selected' : '' }}>Erode</option>
        <option value="Kallakurichi" {{ $indent->pickup_locations === 'Kallakurichi' ? 'selected' : '' }}>Kallakurichi</option>
        <option value="Kancheepuram" {{ $indent->pickup_locations === 'Kancheepuram' ? 'selected' : '' }}>Kancheepuram</option>
        <option value="Karur" {{ $indent->pickup_locations === 'Karur' ? 'selected' : '' }}>Karur</option>
        <option value="Krishnagiri" {{ $indent->pickup_locations === 'Krishnagiri' ? 'selected' : '' }}>Krishnagiri</option>
        <option value="Madurai" {{ $indent->pickup_locations === 'Madurai' ? 'selected' : '' }}>Madurai</option>
        <option value="Mayiladuthurai" {{ $indent->pickup_locations === 'Mayiladuthurai' ? 'selected' : '' }}>Mayiladuthurai</option>
        <option value="Nagapattinam" {{ $indent->pickup_locations === 'Nagapattinam' ? 'selected' : '' }}>Nagapattinam</option>
        <option value="Kanniyakumari" {{ $indent->pickup_locations === 'Kanniyakumari' ? 'selected' : '' }}>Kanniyakumari</option>
        <option value="Namakkal" {{ $indent->pickup_locations === 'Namakkal' ? 'selected' : '' }}>Namakkal</option>
        <option value="Perambalur" {{ $indent->pickup_locations === 'Perambalur' ? 'selected' : '' }}>Perambalur</option>
        <option value="Pudukottai" {{ $indent->pickup_locations === 'Pudukottai' ? 'selected' : '' }}>Pudukottai</option>
        <option value="Ramanathapuram" {{ $indent->pickup_locations === 'Ramanathapuram' ? 'selected' : '' }}>Ramanathapuram</option>
        <option value="Ranipet" {{ $indent->pickup_locations === 'Ranipet' ? 'selected' : '' }}>Ranipet</option>
        <option value="Salem" {{ $indent->pickup_locations === 'Salem' ? 'selected' : '' }}>Salem</option>
        <option value="Sivagangai" {{ $indent->pickup_locations === 'Sivagangai' ? 'selected' : '' }}>Sivagangai</option>
        <option value="Tenkasi" {{ $indent->pickup_locations === 'Tenkasi' ? 'selected' : '' }}>Tenkasi</option>
        <option value="Thanjavur" {{ $indent->pickup_locations === 'Thanjavur' ? 'selected' : '' }}>Thanjavur</option>
        <option value="Theni" {{ $indent->pickup_locations === 'Theni' ? 'selected' : '' }}>Theni</option>
        <option value="Thiruvallur" {{ $indent->pickup_locations === 'Thiruvallur' ? 'selected' : '' }}>Thiruvallur</option>
        <option value="Thiruvarur" {{ $indent->pickup_locations === 'Thiruvarur' ? 'selected' : '' }}>Thiruvarur</option>
        <option value="Thoothukudi" {{ $indent->pickup_locations === 'Thoothukudi' ? 'selected' : '' }}>Thoothukudi</option>
        <option value="Trichirappalli" {{ $indent->pickup_locations === 'Trichirappalli' ? 'selected' : '' }}>Trichirappalli</option>
        <option value="Tirunelveli" {{ $indent->pickup_locations === 'Tirunelveli' ? 'selected' : '' }}>Tirunelveli</option>
        <option value="Tirupathur" {{ $indent->pickup_locations === 'Tirupathur' ? 'selected' : '' }}>Tirupathur</option>
        <option value="Tiruppur" {{ $indent->pickup_locations === 'Tiruppur' ? 'selected' : '' }}>Tiruppur</option>
        <option value="Tiruvannamalai" {{ $indent->pickup_locations === 'Tiruvannamalai' ? 'selected' : '' }}>Tiruvannamalai</option>
        <option value="The Nilgiris" {{ $indent->pickup_locations === 'The Nilgiris' ? 'selected' : '' }}>The Nilgiris</option>
        <option value="Vellore" {{ $indent->pickup_locations === 'Vellore' ? 'selected' : '' }}>Vellore</option>
        <option value="Viluppuram" {{ $indent->pickup_locations === 'Viluppuram' ? 'selected' : '' }}>Viluppuram</option>
        <option value="Virudhunagar" {{ $indent->pickup_locations === 'Virudhunagar' ? 'selected' : '' }}>Virudhunagar</option>
    </select>
</div>

<div class="form-group">
    <label for="drop_locations">Drop Locations:</label>
    <select class="form-control" id="drop_locations" name="drop_locations">
        <option value="">Select Drop Location</option>
        <option value="Ariyalur" {{ $indent->drop_locations === 'Ariyalur' ? 'selected' : '' }}>Ariyalur</option>
        <option value="Chengalpattu" {{ $indent->drop_locations === 'Chengalpattu' ? 'selected' : '' }}>Chengalpattu</option>
        <option value="Chennai" {{ $indent->drop_locations === 'Chennai' ? 'selected' : '' }}>Chennai</option>
        <option value="Coimbatore" {{ $indent->drop_locations === 'Coimbatore' ? 'selected' : '' }}>Coimbatore</option>
        <option value="Cuddalore" {{ $indent->drop_locations === 'Cuddalore' ? 'selected' : '' }}>Cuddalore</option>
        <option value="Dharmapuri" {{ $indent->drop_locations === 'Dharmapuri' ? 'selected' : '' }}>Dharmapuri</option>
        <option value="Dindigul" {{ $indent->drop_locations === 'Dindigul' ? 'selected' : '' }}>Dindigul</option>
        <option value="Erode" {{ $indent->drop_locations === 'Erode' ? 'selected' : '' }}>Erode</option>
        <option value="Kallakurichi" {{ $indent->drop_locations === 'Kallakurichi' ? 'selected' : '' }}>Kallakurichi</option>
        <option value="Kancheepuram" {{ $indent->drop_locations === 'Kancheepuram' ? 'selected' : '' }}>Kancheepuram</option>
        <option value="Karur" {{ $indent->drop_locations === 'Karur' ? 'selected' : '' }}>Karur</option>
        <option value="Krishnagiri" {{ $indent->drop_locations === 'Krishnagiri' ? 'selected' : '' }}>Krishnagiri</option>
        <option value="Madurai" {{ $indent->drop_locations === 'Madurai' ? 'selected' : '' }}>Madurai</option>
        <option value="Mayiladuthurai" {{ $indent->drop_locations === 'Mayiladuthurai' ? 'selected' : '' }}>Mayiladuthurai</option>
        <option value="Nagapattinam" {{ $indent->drop_locations === 'Nagapattinam' ? 'selected' : '' }}>Nagapattinam</option>
        <option value="Kanniyakumari" {{ $indent->drop_locations === 'Kanniyakumari' ? 'selected' : '' }}>Kanniyakumari</option>
        <option value="Namakkal" {{ $indent->drop_locations === 'Namakkal' ? 'selected' : '' }}>Namakkal</option>
        <option value="Perambalur" {{ $indent->drop_locations === 'Perambalur' ? 'selected' : '' }}>Perambalur</option>
        <option value="Pudukottai" {{ $indent->drop_locations === 'Pudukottai' ? 'selected' : '' }}>Pudukottai</option>
        <option value="Ramanathapuram" {{ $indent->drop_locations === 'Ramanathapuram' ? 'selected' : '' }}>Ramanathapuram</option>
        <option value="Ranipet" {{ $indent->drop_locations === 'Ranipet' ? 'selected' : '' }}>Ranipet</option>
        <option value="Salem" {{ $indent->drop_locations === 'Salem' ? 'selected' : '' }}>Salem</option>
        <option value="Sivagangai" {{ $indent->drop_locations === 'Sivagangai' ? 'selected' : '' }}>Sivagangai</option>
        <option value="Tenkasi" {{ $indent->drop_locations === 'Tenkasi' ? 'selected' : '' }}>Tenkasi</option>
        <option value="Thanjavur" {{ $indent->drop_locations === 'Thanjavur' ? 'selected' : '' }}>Thanjavur</option>
        <option value="Theni" {{ $indent->drop_locations === 'Theni' ? 'selected' : '' }}>Theni</option>
        <option value="Thiruvallur" {{ $indent->drop_locations === 'Thiruvallur' ? 'selected' : '' }}>Thiruvallur</option>
        <option value="Thiruvarur" {{ $indent->drop_locations === 'Thiruvarur' ? 'selected' : '' }}>Thiruvarur</option>
        <option value="Thoothukudi" {{ $indent->drop_locations === 'Thoothukudi' ? 'selected' : '' }}>Thoothukudi</option>
        <option value="Trichirappalli" {{ $indent->drop_locations === 'Trichirappalli' ? 'selected' : '' }}>Trichirappalli</option>
        <option value="Tirunelveli" {{ $indent->drop_locations === 'Tirunelveli' ? 'selected' : '' }}>Tirunelveli</option>
        <option value="Tirupathur" {{ $indent->drop_locations === 'Tirupathur' ? 'selected' : '' }}>Tirupathur</option>
        <option value="Tiruppur" {{ $indent->drop_locations === 'Tiruppur' ? 'selected' : '' }}>Tiruppur</option>
        <option value="Tiruvannamalai" {{ $indent->drop_locations === 'Tiruvannamalai' ? 'selected' : '' }}>Tiruvannamalai</option>
        <option value="The Nilgiris" {{ $indent->drop_locations === 'The Nilgiris' ? 'selected' : '' }}>The Nilgiris</option>
        <option value="Vellore" {{ $indent->drop_locations === 'Vellore' ? 'selected' : '' }}>Vellore</option>
        <option value="Viluppuram" {{ $indent->drop_locations === 'Viluppuram' ? 'selected' : '' }}>Viluppuram</option>
        <option value="Virudhunagar" {{ $indent->drop_locations === 'Virudhunagar' ? 'selected' : '' }}>Virudhunagar</option>

    </select>
</div>
