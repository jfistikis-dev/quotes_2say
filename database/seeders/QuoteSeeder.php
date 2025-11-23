<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quote;

class QuoteSeeder extends Seeder
{
    public function run()
    {
        // create 200 quotes
        Quote::insert([
            ["greek"=>"Ἕπου θεῷ","translit"=>"Hepou theōi","translation"=>"Follow the gods","attributed_to"=>"Delphic Maxims (trad.)"],
            ["greek"=>"Νόμῳ πείθου","translit"=>"Nomōi peithou","translation"=>"Obey the law","attributed_to"=>"Delphic Maxims (trad.)"],
            ["greek"=>"Θεοὺς σέβου","translit"=>"Theous sebou","translation"=>"Worship the gods","attributed_to"=>"Delphic Maxims"],
            ["greek"=>"Γονεῖς αἰδοῦ","translit"=>"Goneis aidou","translation"=>"Respect your parents","attributed_to"=>"Delphic Maxims"],
            ["greek"=>"Γνῶθι σεαυτόν","translit"=>"Gnōthi seauton","translation"=>"Know thyself","attributed_to"=>"Delphic Maxims / Seven Sages"],
            ["greek"=>"Μηδὲν ἄγαν","translit"=>"Mēden agan","translation"=>"Nothing in excess","attributed_to"=>"Delphic Maxims / Seven Sages"],
            ["greek"=>"Σαυτὸν ἴσθι","translit"=>"Sauton isthi","translation"=>"Be yourself","attributed_to"=>"Delphic Maxims"],
            ["greek"=>"Ἄρχε σεαυτοῦ","translit"=>"Arche seautou","translation"=>"Rule yourself","attributed_to"=>"Delphic Maxims"],
            ["greek"=>"Φρόνει θνητά","translit"=>"Phronei thnēta","translation"=>"Think like a mortal","attributed_to"=>"Delphic Maxims"],
            ["greek"=>"Παιδείας ἀντέχου","translit"=>"Paideias antechou","translation"=>"Hold fast to education","attributed_to"=>"Delphic Maxims"],
            ["greek"=>"Φίλοις βοήθει","translit"=>"Philois boēthei","translation"=>"Help your friends","attributed_to"=>"Delphic Maxims"],
            ["greek"=>"Θυμοῦ κράτει","translit"=>"Thumou kratei","translation"=>"Control anger","attributed_to"=>"Delphic Maxims"],
            ["greek"=>"Ὅρκῳ μὴ χρῶ","translit"=>"Horkō mē chrō","translation"=>"Do not use an oath","attributed_to"=>"Delphic Maxims"],
            ["greek"=>"Ξένος ὢν ἴσθι","translit"=>"Xenos ōn isthi","translation"=>"If you are a stranger, act like one","attributed_to"=>"Delphic Maxims / Menander"],
            ["greek"=>"Καιρὸν γνῶθι","translit"=>"Kairon gnōthi","translation"=>"Know the right moment","attributed_to"=>"Delphic Maxims"],
            ["greek"=>"Φρόνει","translit"=>"Phronei","translation"=>"Be prudent","attributed_to"=>"Delphic Maxims"],
            ["greek"=>"Ἑστίαν τίμα","translit"=>"Hestian tima","translation"=>"Honor the hearth","attributed_to"=>"Delphic Maxims"],
            ["greek"=>"Πρόνοιαν τίμα","translit"=>"Pronoian tima","translation"=>"Honor providence","attributed_to"=>"Delphic Maxims"],
            ["greek"=>"Φιλίαν ἀγάπα","translit"=>"Philian agapa","translation"=>"Love friendship","attributed_to"=>"Delphic Maxims"],
            ["greek"=>"Ἀκούσας νόει","translit"=>"Akousas noei","translation"=>"Understand what you have heard","attributed_to"=>"Delphic Maxims"]
        ]);
    }
}
