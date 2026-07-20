#!/bin/bash
STORAGE_PATH="storage/app/public/spare_parts"
mkdir -p "$STORAGE_PATH"
echo "Mulai download 20 gambar spare part..."

download_image() {
  local filename="$1"
  local url="$2"
  local output="$STORAGE_PATH/$filename"
  if curl -s -L \
    -H "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36" \
    -H "Referer: https://en.wikipedia.org/" \
    --max-time 15 \
    -o "$output" "$url" && [ -s "$output" ]; then
    echo "  OK $filename"
  else
    echo "  GAGAL: $filename"
    rm -f "$output"
  fi
}

download_image "kampas_rem_depan.jpg" "https://upload.wikimedia.org/wikipedia/commons/thumb/4/45/Disc_brake_pads.JPG/320px-Disc_brake_pads.JPG"
download_image "kampas_rem_belakang.jpg" "https://upload.wikimedia.org/wikipedia/commons/thumb/d/db/Drum_brake_shoe.jpg/320px-Drum_brake_shoe.jpg"
download_image "filter_oli.jpg" "https://upload.wikimedia.org/wikipedia/commons/thumb/7/70/Oil_filter_cutaway.jpg/320px-Oil_filter_cutaway.jpg"
download_image "filter_udara.jpg" "https://upload.wikimedia.org/wikipedia/commons/thumb/e/e2/AirFilter.jpg/320px-AirFilter.jpg"
download_image "busi.jpg" "https://upload.wikimedia.org/wikipedia/commons/thumb/8/8e/Spark_plug_anode.jpg/320px-Spark_plug_anode.jpg"
download_image "aki_mobil.jpg" "https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Lead_acid_battery.jpg/320px-Lead_acid_battery.jpg"
download_image "oli_mesin.jpg" "https://upload.wikimedia.org/wikipedia/commons/thumb/a/a7/Motoroil.jpg/320px-Motoroil.jpg"
download_image "shock_absorber_depan.jpg" "https://upload.wikimedia.org/wikipedia/commons/thumb/1/17/Strut_assembly.jpg/320px-Strut_assembly.jpg"
download_image "shock_absorber_belakang.jpg" "https://upload.wikimedia.org/wikipedia/commons/thumb/9/98/Shock_absorber.jpg/320px-Shock_absorber.jpg"
download_image "timing_belt.jpg" "https://upload.wikimedia.org/wikipedia/commons/thumb/a/a3/Timing_belt_worn.jpg/320px-Timing_belt_worn.jpg"
download_image "v_belt.jpg" "https://upload.wikimedia.org/wikipedia/commons/thumb/3/36/Serpentine_belt.jpg/320px-Serpentine_belt.jpg"
download_image "water_pump.jpg" "https://upload.wikimedia.org/wikipedia/commons/thumb/d/d1/Water_pump_of_a_car.jpg/320px-Water_pump_of_a_car.jpg"
download_image "radiator.jpg" "https://upload.wikimedia.org/wikipedia/commons/thumb/1/1c/Automobile_radiator.jpg/320px-Automobile_radiator.jpg"
download_image "ball_joint.jpg" "https://upload.wikimedia.org/wikipedia/commons/thumb/5/5b/Ball_joint_2.jpg/320px-Ball_joint_2.jpg"
download_image "tie_rod_end.jpg" "https://upload.wikimedia.org/wikipedia/commons/thumb/c/cd/Tie_rod_end.jpg/320px-Tie_rod_end.jpg"
download_image "filter_bahan_bakar.jpg" "https://upload.wikimedia.org/wikipedia/commons/thumb/e/e7/Fuel_filter.jpg/320px-Fuel_filter.jpg"
download_image "kampas_kopling.jpg" "https://upload.wikimedia.org/wikipedia/commons/thumb/5/54/Clutch_disc.jpg/320px-Clutch_disc.jpg"
download_image "minyak_rem.jpg" "https://upload.wikimedia.org/wikipedia/commons/thumb/b/b1/Brake_fluid_reservoir.jpg/320px-Brake_fluid_reservoir.jpg"
download_image "coolant.jpg" "https://upload.wikimedia.org/wikipedia/commons/thumb/9/9b/Coolant_reservoir.jpg/320px-Coolant_reservoir.jpg"
download_image "bearing_roda_depan.jpg" "https://upload.wikimedia.org/wikipedia/commons/thumb/a/a4/Wheel_bearing.jpg/320px-Wheel_bearing.jpg"

echo ""
echo "Selesai! Cek folder: $STORAGE_PATH"
