<?php

namespace Database\Seeders;

use App\Enums\ProductCategory;
use App\Enums\ProductUnit;
use App\Models\Product;
use Illuminate\Database\Seeder;

class MasterProductCatalogSeeder extends Seeder
{
    /**
     * Catálogo maestro inicial. Replica los ~31 productos del sistema anterior
     * pero como UN solo registro cada uno (no duplicados por cliente).
     */
    public function run(): void
    {
        foreach ($this->catalog() as $row) {
            Product::updateOrCreate(
                ['sku' => $row['sku']],
                $row + ['is_default_for_new_clients' => true, 'is_active' => true],
            );
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function catalog(): array
    {
        return [
            ['sku' => 'H-1500', 'name' => 'HIELO 1.5K', 'description' => 'Bolsa de hielo 1.5 kg', 'category' => ProductCategory::Ice->value, 'unit' => ProductUnit::Bag->value, 'default_price' => 3500],
            ['sku' => 'H-5000', 'name' => 'HIELO 5K', 'description' => 'Bolsa de hielo 5 kg', 'category' => ProductCategory::Ice->value, 'unit' => ProductUnit::Bag->value, 'default_price' => 8500],
            ['sku' => 'H-15000', 'name' => 'HIELO 15K', 'description' => 'Bolsa de hielo 15 kg', 'category' => ProductCategory::Ice->value, 'unit' => ProductUnit::Bag->value, 'default_price' => 22000],

            ['sku' => 'A-EDEN-600', 'name' => 'EDEN 600 ML', 'description' => 'Agua Eden 600 ml', 'category' => ProductCategory::Water->value, 'unit' => ProductUnit::Unit->value, 'default_price' => 2200],
            ['sku' => 'A-WICE-600', 'name' => 'WICE 600 ML', 'description' => 'Agua WICE 600 ml', 'category' => ProductCategory::Water->value, 'unit' => ProductUnit::Unit->value, 'default_price' => 2200],
            ['sku' => 'A-KRISS-600', 'name' => 'KRISS 600 ML', 'description' => 'Agua KRISS 600 ml', 'category' => ProductCategory::Water->value, 'unit' => ProductUnit::Unit->value, 'default_price' => 2200],
            ['sku' => 'A-KRISS-1500', 'name' => 'KRISS 1.5 L', 'description' => 'Agua KRISS 1.5 L', 'category' => ProductCategory::Water->value, 'unit' => ProductUnit::Unit->value, 'default_price' => 4200],
            ['sku' => 'A-KRISS-5L', 'name' => 'KRISS 5 L', 'description' => 'Garrafa KRISS 5 L', 'category' => ProductCategory::Water->value, 'unit' => ProductUnit::Unit->value, 'default_price' => 9500],
            ['sku' => 'A-MANA-600', 'name' => 'MANA 600 ML', 'description' => 'Agua MANA 600 ml', 'category' => ProductCategory::Water->value, 'unit' => ProductUnit::Unit->value, 'default_price' => 2200],
            ['sku' => 'A-CRISTAL-600', 'name' => 'CRISTAL 600 ML', 'description' => 'Agua CRISTAL 600 ml', 'category' => ProductCategory::Water->value, 'unit' => ProductUnit::Unit->value, 'default_price' => 2400],
            ['sku' => 'A-CASCADA-600', 'name' => 'CASCADA 600 ML', 'description' => 'Agua CASCADA 600 ml', 'category' => ProductCategory::Water->value, 'unit' => ProductUnit::Unit->value, 'default_price' => 2200],
            ['sku' => 'A-FUENTE-600', 'name' => 'FUENTE 600 ML', 'description' => 'Agua FUENTE 600 ml', 'category' => ProductCategory::Water->value, 'unit' => ProductUnit::Unit->value, 'default_price' => 2200],
            ['sku' => 'A-ESMERALDA-600', 'name' => 'ESMERALDA 600 ML', 'description' => 'Agua ESMERALDA 600 ml', 'category' => ProductCategory::Water->value, 'unit' => ProductUnit::Unit->value, 'default_price' => 2200],
            ['sku' => 'A-GOTAAZUL-600', 'name' => 'GOTA AZUL 600 ML', 'description' => 'Agua GOTA AZUL 600 ml', 'category' => ProductCategory::Water->value, 'unit' => ProductUnit::Unit->value, 'default_price' => 2200],
            ['sku' => 'A-LIBERTAD-600', 'name' => 'LIBERTADORES 600 ML', 'description' => 'Agua LIBERTADORES 600 ml', 'category' => ProductCategory::Water->value, 'unit' => ProductUnit::Unit->value, 'default_price' => 2200],
            ['sku' => 'A-ANTARTIKA-600', 'name' => 'ANTARTIKA 600 ML', 'description' => 'Agua ANTARTIKA 600 ml', 'category' => ProductCategory::Water->value, 'unit' => ProductUnit::Unit->value, 'default_price' => 2200],
            ['sku' => 'A-ARTIKA-600', 'name' => 'ARTIKA 600 ML', 'description' => 'Agua ARTIKA 600 ml', 'category' => ProductCategory::Water->value, 'unit' => ProductUnit::Unit->value, 'default_price' => 2200],

            ['sku' => 'A-KRISS-PK24', 'name' => 'KRISS PAQUETE x24', 'description' => 'Paquete 24 unid. KRISS 600 ml', 'category' => ProductCategory::Water->value, 'unit' => ProductUnit::Pack->value, 'default_price' => 38000],
            ['sku' => 'A-EDEN-PK24', 'name' => 'EDEN PAQUETE x24', 'description' => 'Paquete 24 unid. EDEN 600 ml', 'category' => ProductCategory::Water->value, 'unit' => ProductUnit::Pack->value, 'default_price' => 38000],
            ['sku' => 'A-MANA-PK24', 'name' => 'MANA PAQUETE x24', 'description' => 'Paquete 24 unid. MANA 600 ml', 'category' => ProductCategory::Water->value, 'unit' => ProductUnit::Pack->value, 'default_price' => 38000],
            ['sku' => 'A-CRISTAL-PK24', 'name' => 'CRISTAL PAQUETE x24', 'description' => 'Paquete 24 unid. CRISTAL 600 ml', 'category' => ProductCategory::Water->value, 'unit' => ProductUnit::Pack->value, 'default_price' => 42000],

            ['sku' => 'A-KRISS-CJ', 'name' => 'KRISS CAJA', 'description' => 'Caja KRISS', 'category' => ProductCategory::Water->value, 'unit' => ProductUnit::Box->value, 'default_price' => 76000],
            ['sku' => 'A-EDEN-CJ', 'name' => 'EDEN CAJA', 'description' => 'Caja EDEN', 'category' => ProductCategory::Water->value, 'unit' => ProductUnit::Box->value, 'default_price' => 76000],
            ['sku' => 'A-WICE-CJ', 'name' => 'WICE CAJA', 'description' => 'Caja WICE', 'category' => ProductCategory::Water->value, 'unit' => ProductUnit::Box->value, 'default_price' => 76000],

            ['sku' => 'EV-KRISS-1500', 'name' => 'ENVASE VACÍO KRISS 1.5L', 'description' => 'Devolución envase vacío KRISS 1.5L', 'category' => ProductCategory::EmptyContainer->value, 'unit' => ProductUnit::Unit->value, 'default_price' => 500],
            ['sku' => 'EV-KRISS-5L', 'name' => 'ENVASE VACÍO KRISS 5L', 'description' => 'Devolución envase vacío KRISS 5L', 'category' => ProductCategory::EmptyContainer->value, 'unit' => ProductUnit::Unit->value, 'default_price' => 1500],
            ['sku' => 'EV-EDEN-5L', 'name' => 'ENVASE VACÍO EDEN 5L', 'description' => 'Devolución envase vacío EDEN 5L', 'category' => ProductCategory::EmptyContainer->value, 'unit' => ProductUnit::Unit->value, 'default_price' => 1500],

            ['sku' => 'NEV-ICO-S', 'name' => 'NEVERA ICOPOR PEQUEÑA', 'description' => 'Nevera de icopor pequeña', 'category' => ProductCategory::Cooler->value, 'unit' => ProductUnit::Unit->value, 'default_price' => 8000],
            ['sku' => 'NEV-ICO-M', 'name' => 'NEVERA ICOPOR MEDIANA', 'description' => 'Nevera de icopor mediana', 'category' => ProductCategory::Cooler->value, 'unit' => ProductUnit::Unit->value, 'default_price' => 14000],
            ['sku' => 'NEV-ICO-L', 'name' => 'NEVERA ICOPOR GRANDE', 'description' => 'Nevera de icopor grande', 'category' => ProductCategory::Cooler->value, 'unit' => ProductUnit::Unit->value, 'default_price' => 22000],

            ['sku' => 'OTR-VARIO', 'name' => 'PRODUCTO VARIO', 'description' => 'Producto vario / promoción', 'category' => ProductCategory::Other->value, 'unit' => ProductUnit::Unit->value, 'default_price' => 1000],
        ];
    }
}
