<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\KontenEdukasi;
use App\Models\Shelter;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== Demo adopter =====
        User::firstOrCreate(
            ['email' => 'adopter@pawrise.id'],
            [
                'name'     => 'Sarah Wijaya',
                'phone'    => '081234567890',
                'password' => bcrypt('password'),
                'role'     => 'adopter',
                'address'  => 'Jl. Sudirman No. 1, Jakarta',
                'bio'      => 'Pencinta hewan, sudah memelihara kucing selama 5 tahun.',
            ]
        );

        // ===== Shelters (sesuai Figma) =====
        $sh1User = User::firstOrCreate(
            ['email' => 'shelter@pawrise.id'],
            ['name' => 'Admin Shelter Harapan', 'phone' => '081234500000', 'password' => bcrypt('password'), 'role' => 'shelter']
        );
        $shHarapan = Shelter::updateOrCreate(
            ['user_id' => $sh1User->id],
            [
                'shelter_name' => 'Shelter Harapan',
                'city'         => 'Jakarta',
                'description'  => 'Shelter independen yang merawat anjing dan kucing terlantar di Jakarta.',
                'phone'        => '081234500000',
            ]
        );

        $sh2User = User::firstOrCreate(
            ['email' => 'klinik-sehat@pawrise.id'],
            ['name' => 'Admin Klinik Hewan Sehat', 'phone' => '082234500000', 'password' => bcrypt('password'), 'role' => 'shelter']
        );
        $shKlinik = Shelter::updateOrCreate(
            ['user_id' => $sh2User->id],
            [
                'shelter_name' => 'Klinik Hewan Sehat',
                'city'         => 'Bandung',
                'description'  => 'Klinik dan rumah perawatan hewan terlantar di Bandung.',
                'phone'        => '082234500000',
            ]
        );

        $sh3User = User::firstOrCreate(
            ['email' => 'paws-rescue@pawrise.id'],
            ['name' => 'Admin Paws Rescue', 'phone' => '083334500000', 'password' => bcrypt('password'), 'role' => 'shelter']
        );
        $shPaws = Shelter::updateOrCreate(
            ['user_id' => $sh3User->id],
            [
                'shelter_name' => 'Paws Rescue',
                'city'         => 'Bali',
                'description'  => 'Komunitas penyelamat hewan jalanan di Bali.',
                'phone'        => '083334500000',
            ]
        );

        // ===== Animal photo helper (Unsplash) =====
        $img = fn(string $id) => "https://images.unsplash.com/{$id}?w=600&h=450&fit=crop";

        // ===== Filler animals (20) =====
        $fillers = [
            ['Bruno','anjing','Golden Retriever',24,22,'jantan','besar', 'photo-1552053831-71594a27632d', $shHarapan],
            ['Mochi','kucing','Persia',8,3,'betina','kecil', 'photo-1514888286974-6c03e2ca1dba', $shHarapan],
            ['Rocky','anjing','Labrador Mix',36,20,'jantan','sedang','photo-1587300003388-59208cc962cb', $shKlinik],
            ['Bella','kucing','Maine Coon',30,5,'betina','sedang','photo-1526336024174-e58f5cdd8e13', $shHarapan],
            ['Max','anjing','Beagle',18,10,'jantan','sedang','photo-1561037404-61cd46aa615b', $shKlinik],
            ['Coco','kucing','Domestic Shorthair',12,3,'betina','kecil','photo-1574158622682-e40e69881006', $shPaws],
            ['Rex','anjing','German Shepherd',48,28,'jantan','besar', 'photo-1568572933382-74d440642117', $shKlinik],
            ['Snowy','kucing','Anggora',6,2,'betina','kecil','photo-1495360010541-f48722b34f7d', $shHarapan],
            ['Buddy','anjing','Pomeranian',14,4,'jantan','kecil','photo-1583512603805-3cc6b41f3edb', $shHarapan],
            ['Chika','kucing','Calico',20,4,'betina','kecil','photo-1592194996308-7b43878e84a6', $shKlinik],
            ['Oreo','kucing','Tuxedo',16,4,'jantan','sedang','photo-1573865526739-10659fec78a5', $shPaws],
            ['Daisy','anjing','Shih Tzu',26,5,'betina','kecil','photo-1601758228041-f3b2795255f1', $shHarapan],
            ['Toby','anjing','Husky',40,24,'jantan','besar','photo-1605568427561-40dd23c2acea', $shPaws],
            ['Nala','kucing','British Shorthair',22,4,'betina','sedang','photo-1561948955-570b270e7c36', $shKlinik],
            ['Simba','kucing','Tabby',9,3,'jantan','kecil','photo-1543852786-1cf6624b9987', $shHarapan],
            ['Charlie','anjing','Corgi',32,12,'jantan','sedang','photo-1612536057832-2ff7ead58194', $shKlinik],
            ['Lulu','kucing','Ragdoll',28,5,'betina','sedang','photo-1518791841217-8f162f1e1131', $shPaws],
            ['Kiko','anjing','Mini Poodle',14,5,'betina','kecil','photo-1517423440428-a5a00ad493e8', $shHarapan],
            ['Pluto','anjing','Dalmatian',30,22,'jantan','besar','photo-1583337130417-3346a1be7dee', $shKlinik],
            ['Mila','kucing','Siamese',18,4,'betina','kecil','photo-1596854407944-bf87f6fdd49e', $shPaws],
        ];

        foreach ($fillers as $i => $f) {
            [$name,$species,$breed,$age,$weight,$gender,$size,$photoId,$shelter] = $f;
            Animal::updateOrCreate(
                ['code' => 'PAW-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT)],
                [
                    'shelter_id'      => $shelter->id,
                    'name'            => $name,
                    'species'         => $species,
                    'breed'           => $breed,
                    'age_months'      => $age,
                    'weight_kg'       => $weight,
                    'gender'          => $gender,
                    'size'            => $size,
                    'vaccinated'      => true,
                    'sterilized'      => $i % 2 === 0,
                    'description'     => "{$name} adalah {$species} ramah yang sedang menunggu keluarga baru.",
                    'characteristics' => 'Ramah, sehat, sudah divaksin',
                    'main_photo'      => $img($photoId),
                    'status'          => 'tersedia',
                ]
            );
        }

        // ===== Starring 4 =====
        $starring = [
            [
                'code'=>'PAW-021','name'=>'Ireng','species'=>'kucing','breed'=>'Bombay Mix',
                'age_months'=>12,'weight_kg'=>3,'gender'=>'jantan','size'=>'kecil',
                'shelter_id'=>$shHarapan->id,
                'description'=>'Ireng kucing remaja dengan mata indah, pemalu tapi sangat manja saat dekat.',
                'characteristics'=>'Pemalu, manja, lembut',
                'main_photo'=> 'attached_assets/Ireng.png',
            ],
            [
                'code'=>'PAW-022','name'=>'Ambatubus','species'=>'anjing','breed'=>'Golden Retriever Mix',
                'age_months'=>36,'weight_kg'=>20,'gender'=>'jantan','size'=>'besar',
                'shelter_id'=>$shPaws->id,
                'description'=>'Ambatubus anjing dewasa yang penyayang dan setia. Sudah terlatih dasar.',
                'characteristics'=>'Setia, tenang, mudah dilatih',
                'main_photo'=> 'attached_assets/Ambatubus.png',
            ],
            [
                'code'=>'PAW-023','name'=>'Luna','species'=>'kucing','breed'=>'Domestik Shorthair',
                'age_months'=>4,'weight_kg'=>2,'gender'=>'betina','size'=>'kecil',
                'shelter_id'=>$shKlinik->id,
                'description'=>'Luna kitten manja yang baru saja diselamatkan. Lincah dan suka bermain.',
                'characteristics'=>'Lincah, manja, ceria',
                'main_photo'=> 'attached_assets/Luna.png',
            ],
            [
                'code'=>'PAW-024','name'=>'Milo','species'=>'anjing','breed'=>'Beagle Mix',
                'age_months'=>24,'weight_kg'=>9,'gender'=>'jantan','size'=>'sedang',
                'shelter_id'=>$shHarapan->id,
                'description'=>'Milo adalah Beagle Mix yang ceria dan suka berlari. Cocok untuk keluarga aktif.',
                'characteristics'=>'Energik, ramah, suka anak-anak',
                'main_photo' => 'attached_assets/Milo.png',
            ],
        ];

        foreach ($starring as $a) {
            Animal::updateOrCreate(
                ['code' => $a['code']],
                array_merge($a, ['vaccinated' => true, 'sterilized' => false, 'status' => 'tersedia'])
            );
        }

        // ===== Admin =====
        $admin = User::firstOrCreate(
            ['email' => 'admin@pawrise.id'],
            [
                'name'     => 'Admin PawRise',
                'phone'    => '081111111111',
                'password' => bcrypt('password'),
                'role'     => 'admin',
            ]
        );

        // ===== Edukasi =====
        KontenEdukasi::firstOrCreate(
            ['slug' => 'panduan-hari-pertama-sambut-anggota-keluarga-baru'],
            [
                'admin_id' => $admin->id,
                'judul' => 'Panduan Hari Pertama: Sambut Anggota Keluarga Baru',
                'ringkasan' => 'Membawa pulang hewan peliharaan baru adalah momen mendebarkan. Inilah yang perlu Anda siapkan agar mereka merasa aman dan nyaman.',
                'konten' => "Membawa hewan peliharaan baru ke rumah adalah momen yang menyenangkan sekaligus penuh tanggung jawab. Baik itu anjing, kucing, maupun hewan peliharaan lainnya, hari pertama sangat menentukan bagaimana mereka akan beradaptasi dengan lingkungan baru. Perubahan tempat, suara, aroma, dan orang-orang di sekitar bisa membuat hewan merasa bingung, takut, bahkan stres. Karena itu, penting bagi pemilik untuk menciptakan suasana yang aman, nyaman, dan penuh kasih sejak awal.\n\nArtikel ini akan membantu kamu memahami langkah-langkah penting dalam menyambut anggota keluarga baru agar proses adaptasi berjalan lebih mudah dan menyenangkan bagi semua pihak.\n\n1. Siapkan Rumah Sebelum Hewan Datang\n\nSebelum membawa hewan peliharaan pulang, pastikan rumah sudah aman dan siap untuk mereka tinggali. Hewan, terutama yang masih kecil atau baru diadopsi, cenderung penasaran dan suka menjelajah.\n\nBeberapa hal yang perlu dipersiapkan:\n- Tempat tidur atau alas tidur yang nyaman\n- Tempat makan dan minum\n- Makanan sesuai usia dan jenis hewan\n- Kotak pasir untuk kucing\n- Mainan sederhana\n- Kandang atau area aman untuk beristirahat\n- Obat atau perlengkapan dasar jika diperlukan\n\nSelain itu, singkirkan benda berbahaya seperti:\n- Kabel listrik terbuka\n- Tanaman beracun\n- Makanan manusia yang tidak aman untuk hewan\n- Cairan pembersih dan bahan kimia\n\nLingkungan yang aman akan membantu hewan merasa lebih tenang saat pertama kali tiba.\n\n2. Berikan Waktu untuk Beradaptasi\n\nHari pertama sering kali menjadi pengalaman yang menegangkan bagi hewan baru. Mereka meninggalkan lingkungan lama dan harus mengenal tempat asing. Jangan memaksa mereka langsung aktif atau bermain.\n\nBiarkan hewan:\n- Mengendus dan menjelajah rumah perlahan\n- Menemukan tempat nyaman sendiri\n- Beristirahat jika terlihat lelah atau takut\n\nJika hewan bersembunyi, itu normal. Hindari menarik atau memaksa mereka keluar. Kesabaran adalah kunci utama dalam proses adaptasi.\n\n3. Perkenalkan Anggota Keluarga Secara Bertahap\n\nHewan baru bisa merasa kewalahan jika langsung dikerumuni banyak orang. Kenalkan anggota keluarga satu per satu dengan suara lembut dan gerakan yang tenang.\n\nAjarkan anak-anak untuk:\n- Tidak berteriak\n- Tidak menarik ekor atau telinga\n- Tidak mengganggu saat hewan makan atau tidur\n\nInteraksi pertama yang positif akan membantu membangun rasa percaya antara hewan dan keluarga barunya.\n\n4. Sediakan Area Aman\n\nHewan membutuhkan tempat khusus untuk merasa aman, terutama saat mereka stres atau takut. Area aman bisa berupa:\n- Sudut ruangan yang tenang\n- Kandang terbuka\n- Tempat tidur pribadi\n- Ruang kecil yang minim gangguan\n\nBiarkan hewan memiliki ruang pribadi. Jangan terus-menerus menggendong atau mengajak bermain jika mereka belum siap.\n\n5. Perhatikan Pola Makan dan Minum\n\nBeberapa hewan mungkin tidak langsung mau makan di hari pertama karena stres. Ini cukup normal selama mereka tetap minum dan tidak menunjukkan tanda sakit.\n\nTips penting:\n- Gunakan makanan yang sama seperti sebelumnya jika memungkinkan\n- Sediakan air bersih setiap saat\n- Jangan langsung mengganti jenis makanan secara mendadak\n\nJika hewan tidak mau makan lebih dari 24 jam atau terlihat lemas, segera konsultasikan ke dokter hewan.\n\n6. Jangan Langsung Memandikan Hewan\n\nBanyak pemilik baru ingin langsung memandikan hewan setelah sampai di rumah. Padahal, hari pertama sebaiknya difokuskan untuk adaptasi emosional terlebih dahulu.\n\nMemandikan terlalu cepat bisa menambah stres, terutama bagi:\n- Kucing\n- Anak anjing\n- Hewan rescue atau hasil adopsi\n\nTunggu sampai hewan terlihat lebih tenang dan nyaman dengan lingkungan baru.\n\n7. Mulai Bangun Rutinitas\n\nHewan peliharaan lebih mudah merasa aman jika memiliki rutinitas yang konsisten. Mulailah membiasakan:\n- Jam makan\n- Waktu bermain\n- Waktu tidur\n- Jadwal buang air\n- Latihan sederhana\n\nRutinitas membantu hewan memahami lingkungan dan membuat mereka lebih cepat beradaptasi.\n\n8. Kenali Bahasa Tubuh Hewan\n\nHewan tidak bisa berbicara, tetapi mereka menunjukkan perasaan melalui perilaku dan bahasa tubuh.\n\nTanda hewan nyaman:\n- Mau makan\n- Ekor rileks\n- Mau mendekat\n- Tidur dengan tenang\n\nTanda hewan stres:\n- Bersembunyi terus\n- Mendesis atau menggonggong berlebihan\n- Gemetar\n- Tidak mau makan\n- Agresif saat disentuh\n\nMemahami bahasa tubuh membantu kamu merespons kebutuhan mereka dengan lebih baik.\n\n9. Jadwalkan Pemeriksaan ke Dokter Hewan\n\nJika hewan baru diadopsi atau belum memiliki riwayat kesehatan yang jelas, lakukan pemeriksaan kesehatan secepatnya.\n\nPemeriksaan biasanya meliputi:\n- Kondisi umum tubuh\n- Vaksinasi\n- Pemeriksaan kutu atau parasit\n- Konsultasi makanan\n- Sterilisasi jika diperlukan\n\nLangkah ini penting untuk memastikan hewan dalam kondisi sehat dan aman tinggal bersama keluarga.\n\n10. Bangun Hubungan dengan Kesabaran dan Kasih Sayang\n\nIkatan antara manusia dan hewan tidak terbentuk dalam satu hari. Beberapa hewan cepat akrab, sementara yang lain membutuhkan waktu lebih lama.\n\nHal terpenting adalah:\n- Bersikap sabar\n- Konsisten\n- Tidak menggunakan kekerasan\n- Memberikan perhatian yang cukup\n\nSemakin aman dan dicintai hewan merasa, semakin mudah mereka mempercayai keluarga barunya.\n\nPenutup\n\nHari pertama adalah awal dari perjalanan panjang bersama sahabat baru di rumah. Dengan persiapan yang baik, suasana yang tenang, dan perhatian penuh kasih, hewan peliharaan akan lebih mudah beradaptasi dan merasa aman.\n\nMengadopsi atau memelihara hewan bukan hanya soal memberi makan, tetapi juga tentang memberikan rumah, rasa nyaman, dan cinta. Setiap langkah kecil yang kamu lakukan di hari pertama akan membantu menciptakan hubungan yang sehat dan bahagia untuk tahun-tahun berikutnya.\n\nSelamat menyambut anggota keluarga baru!",
                'kategori' => 'gaya_hidup',
                'estimasi_baca' => 5,
                'gambar' => 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?w=800&q=80',
                'is_published' => true,
                'published_at' => now(),
            ]
        );

        KontenEdukasi::firstOrCreate(
            ['slug' => 'nutrisi-seimbang-apa-yang-sebenarnya-mereka-butuhkan'],
            [
                'admin_id' => $admin->id,
                'judul' => 'Nutrisi Seimbang: Apa yang Sebenarnya Mereka Butuhkan?',
                'ringkasan' => 'Pahami kebutuhan nutrisi spesifik berdasarkan usia dan jenis hewan untuk kesehatan jangka panjang yang optimal.',
                'konten' => "Sama seperti manusia, hewan peliharaan membutuhkan asupan gizi yang seimbang untuk hidup sehat dan bahagia. Makanan yang tepat tidak hanya memberikan energi, tetapi juga memperkuat sistem kekebalan tubuh, menjaga kesehatan bulu dan kulit, serta memperpanjang usia mereka. Namun, dengan banyaknya pilihan makanan hewan di pasaran, sering kali membingungkan untuk menentukan mana yang benar-benar mereka butuhkan.\n\nArtikel ini akan mengupas tuntas dasar-dasar nutrisi hewan peliharaan dan apa saja yang harus diperhatikan dalam memilih makanan untuk teman berbulu kamu.\n\n1. Enam Nutrisi Dasar yang Dibutuhkan Hewan\n\nUntuk mendapatkan diet yang seimbang, hewan peliharaan membutuhkan enam kelompok nutrisi utama:\n- Air: Air adalah elemen paling penting. Tubuh hewan, terutama kucing dan anjing, sangat rentan terhadap dehidrasi. Pastikan mangkuk air selalu terisi dengan air bersih setiap hari.\n- Protein: Penting untuk membangun dan memperbaiki otot, jaringan, dan sistem kekebalan. Protein sangat krusial, terutama bagi kucing yang merupakan karnivora obligat.\n- Lemak: Lemak memberikan energi terkonsentrasi dan membantu tubuh menyerap vitamin (seperti vitamin A, D, E, dan K). Lemak juga menjaga kesehatan kulit dan kilau bulu.\n- Karbohidrat: Meskipun bukan sumber energi utama bagi beberapa hewan, karbohidrat berkualitas (seperti biji-bijian utuh) menyediakan serat yang penting untuk kesehatan pencernaan.\n- Vitamin: Dibutuhkan dalam jumlah kecil untuk mengatur berbagai proses dalam tubuh, seperti pertumbuhan tulang (Vitamin D) dan fungsi penglihatan (Vitamin A).\n- Mineral: Kalsium dan fosfor sangat penting untuk kesehatan tulang dan gigi, sementara zat besi membantu dalam transportasi oksigen dalam darah.\n\n2. Kebutuhan Nutrisi Berubah Sesuai Tahap Kehidupan\n\nSatu jenis makanan tidak cocok untuk semua fase kehidupan hewan.\n- Anak Anjing dan Kucing (Puppy & Kitten): Mereka membutuhkan kalori, protein, lemak, kalsium, dan fosfor yang lebih tinggi untuk mendukung pertumbuhan tulang, otot, dan perkembangan otak yang pesat.\n- Hewan Dewasa (Adult): Fokus utamanya adalah pemeliharaan. Terlalu banyak kalori di tahap ini bisa menyebabkan obesitas. Pilih makanan seimbang yang sesuai dengan tingkat aktivitas mereka.\n- Hewan Senior: Saat metabolisme menurun, mereka membutuhkan makanan rendah kalori namun mudah dicerna. Suplemen tambahan seperti glucosamine untuk kesehatan sendi sering kali sangat membantu.\n\n3. Perbedaan Kebutuhan Kucing dan Anjing\n\nSangat penting untuk tidak memberikan makanan anjing kepada kucing, dan sebaliknya, karena kebutuhan mereka secara biologis berbeda:\n- Kucing (Karnivora Obligat): Mereka mutlak membutuhkan daging. Kucing tidak bisa memproduksi taurin (asam amino esensial) dan asam arakidonat sendiri, sehingga harus didapatkan langsung dari protein hewani.\n- Anjing (Omnivora): Anjing lebih fleksibel. Meskipun daging tetap menjadi komponen utama, mereka dapat mencerna dan mendapatkan nutrisi dari sayuran, buah, dan biji-bijian.\n\n4. Makanan Basah (Wet Food) vs Makanan Kering (Dry Food)\n\nKeduanya memiliki kelebihan masing-masing:\n- Dry Food: Lebih tahan lama, mudah disimpan, dan harganya lebih terjangkau. Teksturnya yang keras juga bisa membantu mengurangi penumpukan plak pada gigi.\n- Wet Food: Memiliki kandungan air yang tinggi, sangat baik untuk hewan yang jarang minum (terutama kucing) sehingga membantu menjaga kesehatan saluran kemih. Selain itu, aromanya lebih kuat dan lebih disukai oleh hewan yang sedang sakit atau rewel makan.\nKombinasi keduanya (mixed feeding) sering direkomendasikan untuk menyeimbangkan asupan cairan dan menjaga kebersihan gigi.\n\n5. Bahaya Makanan Manusia bagi Hewan Peliharaan\n\nTidak semua makanan yang aman bagi manusia aman untuk hewan. Sistem pencernaan mereka berbeda. Hindari memberikan makanan berikut karena bisa sangat beracun dan fatal:\n- Cokelat, kopi, dan teh (mengandung kafein dan theobromine)\n- Anggur dan kismis (menyebabkan gagal ginjal pada anjing)\n- Bawang merah dan bawang putih (merusak sel darah merah)\n- Alpukat (mengandung persin yang beracun bagi banyak hewan)\n- Xylitol (pemanis buatan pada permen atau selai kacang yang bisa memicu gagal hati)\n- Tulang matang (mudah pecah dan bisa melukai organ dalam)\n\n6. Tanda-Tanda Hewan Mendapat Nutrisi yang Baik\n\nJika diet yang diberikan sudah tepat, kamu akan melihat ciri-ciri fisik berikut pada hewan kesayanganmu:\n- Bulu yang halus, berkilau, dan tidak rontok berlebihan\n- Kulit yang bersih tanpa ketombe atau luka\n- Mata yang cerah dan jernih\n- Energi yang stabil dan tidak mudah lelah\n- Feses yang padat, bentuknya jelas, dan jumlahnya tidak berlebihan\n\nKesimpulan\n\nMemberikan nutrisi seimbang bukan sekadar membuat mereka kenyang, tetapi berinvestasi pada masa depan kesehatan mereka. Selalu perhatikan label makanan, sesuaikan dengan umur dan kondisi kesehatan, serta jangan ragu untuk berkonsultasi dengan dokter hewan guna mendapatkan rekomendasi diet yang paling ideal.\n\nDengan makanan yang tepat, hewan peliharaanmu bisa hidup lebih panjang, sehat, dan tentu saja, lebih bahagia menemanimu setiap hari.",
                'kategori' => 'nutrisi',
                'estimasi_baca' => 8,
                'gambar' => 'https://images.unsplash.com/photo-1583337130417-3346a1be7dee?w=800&q=80',
                'is_published' => true,
                'published_at' => now(),
            ]
        );

        KontenEdukasi::firstOrCreate(
            ['slug' => 'mengenal-tanda-tanda-hewan-sakit'],
            [
                'admin_id' => $admin->id,
                'judul' => 'Mengenal Tanda-tanda Hewan Sakit',
                'ringkasan' => 'Deteksi dini dapat menyelamatkan nyawa. Kenali perubahan perilaku dan fisik yang memerlukan perhatian medis segera.',
                'konten' => "Hewan peliharaan tidak bisa berbicara untuk memberi tahu kita bahwa mereka merasa sakit. Terlebih lagi, secara naluriah—terutama kucing dan beberapa jenis anjing—mereka cenderung menyembunyikan kelemahan dan rasa sakit sebagai bentuk pertahanan diri warisan dari nenek moyang mereka di alam liar.\n\nOleh karena itu, tanggung jawab kita sebagai pemilik adalah menjadi sangat jeli terhadap perubahan sekecil apa pun. Mengenali tanda-tanda awal penyakit bukan hanya bisa mempercepat proses penyembuhan, tetapi juga dapat menyelamatkan nyawa mereka.\n\nBerikut adalah tanda-tanda umum yang mengindikasikan bahwa hewan peliharaan kamu mungkin sedang sakit dan membutuhkan pertolongan medis segera.\n\n1. Perubahan Pola Makan dan Minum\n\nIni adalah salah satu indikator paling umum dan mudah dikenali.\n- Kehilangan Nafsu Makan: Jika hewan tidak mau makan selama lebih dari 24 jam (atau lebih dari 12 jam untuk anak kucing/anjing), ini adalah tanda bahaya.\n- Makan Terlalu Banyak (Polifagia): Nafsu makan yang meningkat drastis tanpa penambahan berat badan bisa menjadi tanda diabetes, gangguan tiroid, atau parasit.\n- Perubahan Minum: Minum dalam jumlah yang jauh lebih banyak dari biasanya (Polidipsia) sering kali mengindikasikan masalah ginjal, diabetes, atau infeksi saluran kemih.\n\n2. Kelesuan dan Perubahan Tingkah Laku\n\nHewan yang sehat umumnya aktif dan memiliki rutinitas yang konsisten.\n- Lesu Berlebihan: Tidur lebih lama dari biasanya, enggan diajak bermain, atau tidak merespons panggilan adalah tanda mereka tidak merasa enak badan.\n- Menarik Diri/Bersembunyi: Kucing, khususnya, akan bersembunyi di tempat-tempat gelap atau sulit dijangkau (seperti di bawah kasur atau di dalam lemari) saat mereka kesakitan.\n- Agresivitas Tiba-tiba: Jika hewan yang biasanya jinak tiba-tiba mendesis, menggeram, atau menggigit saat disentuh, kemungkinan besar mereka sedang merasakan sakit pada bagian tubuh tersebut.\n\n3. Gangguan Pencernaan (Muntah dan Diare)\n\nMuntah sesekali mungkin normal (misalnya kucing yang mengeluarkan hairball), tetapi menjadi tidak normal jika:\n- Muntah terjadi berkali-kali dalam sehari.\n- Muntah disertai darah, atau berwarna hijau/kuning pekat.\n- Diare berlangsung lebih dari 24 jam, atau tinja mengandung darah dan lendir.\n- Mengejan tanpa hasil saat buang air besar (sembelit parah).\n\n4. Kesulitan Buang Air Kecil\n\nMasalah saluran kemih sangat sering terjadi, terutama pada kucing jantan, dan ini merupakan kondisi darurat medis.\n- Tanda Bahaya: Hewan bolak-balik ke litter box/tempat buang air, mengejan, menangis saat mencoba buang air kecil, atau hanya keluar beberapa tetes urine.\n- Urine Berdarah: Jika terdapat warna merah atau merah muda pada urine mereka.\nJika saluran kemih tersumbat dan tidak segera ditangani, racun akan menumpuk di ginjal dan dapat berakibat fatal dalam hitungan jam.\n\n5. Masalah Pernapasan\n\nMasalah pada sistem pernapasan tidak boleh diremehkan.\n- Tanda Bahaya: Napas tersengal-sengal tanpa ada aktivitas fisik, napas pendek dan cepat, batuk yang terus-menerus, bersin berlebihan, atau keluar cairan kental dari hidung.\n- Mulut Terbuka: Kucing normalnya bernapas melalui hidung. Jika kucing bernapas dengan mulut terbuka (panting), itu tanda stres pernapasan parah.\n\n6. Perubahan Fisik dan Penampilan\n\nPerhatikan kondisi tubuh mereka secara keseluruhan saat membelai atau menyisir bulu mereka:\n- Penurunan atau Kenaikan Berat Badan yang Drastis tanpa perubahan porsi makan.\n- Bulu Kusam dan Rontok Parah: Sering kali disertai dengan kulit yang memerah, kering, berketombe, atau hewan yang terus-menerus menggaruk dan menjilati area tertentu secara obsesif.\n- Benjolan Baru: Jika kamu meraba ada benjolan atau pembengkakan yang tumbuh cepat, hangat, atau membuat hewan kesakitan saat disentuh.\n\n7. Masalah pada Mata, Telinga, dan Mulut\n\nArea wajah sering kali menjadi jendela kesehatan hewan peliharaan.\n- Mata: Berair terus-menerus, kemerahan, terlihat keruh, banyak kotoran mata (belekan), atau sering memicingkan mata.\n- Telinga: Bau tidak sedap dari dalam telinga, banyak kotoran berwarna gelap, sering menggelengkan kepala, atau terus-menerus menggaruk telinga.\n- Mulut: Bau mulut yang sangat tajam, gusi terlihat sangat merah, bengkak, berdarah, atau pucat pasi (gusi pucat menandakan anemia atau syok), serta banyak mengeluarkan air liur (hipersalivasi).\n\nKapan Harus Segera ke Dokter Hewan?\n\nJangan menunda kunjungan ke klinik atau dokter hewan jika hewan peliharaan kamu mengalami:\n- Kejang-kejang\n- Kesulitan bernapas atau napas tersengal-sengal parah\n- Pendarahan parah yang tidak kunjung berhenti\n- Ketidakmampuan untuk berdiri atau anggota tubuh lumpuh tiba-tiba\n- Menelan racun atau benda asing\n- Tidak sadarkan diri\n\nKesimpulan\n\nSebagai sahabat terbaik mereka, kamu adalah garis pertahanan pertama bagi kesehatan hewan peliharaan. Percayalah pada instingmu—jika kamu merasa ada sesuatu yang \"tidak beres\" dengan tingkah laku atau kondisi fisik mereka, lebih baik bermain aman dan segera periksakan ke dokter hewan. Deteksi dini selalu memberikan peluang pemulihan yang jauh lebih baik.",
                'kategori' => 'kesehatan',
                'estimasi_baca' => 6,
                'gambar' => 'https://images.unsplash.com/photo-1596854407944-bf87f6fdd49e?w=800&q=80',
                'is_published' => true,
                'published_at' => now(),
            ]
        );
    }
}