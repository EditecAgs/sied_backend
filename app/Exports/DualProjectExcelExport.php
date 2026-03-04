<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

class DualProjectExcelExport implements FromArray, WithHeadings, WithTitle, ShouldAutoSize, WithStyles, WithColumnWidths, WithEvents
{
    protected $data;
    protected $filters;

    const BRAND_800 = 'FF7A1F2B';
    const BRAND_900 = 'FF460809';
    const BRAND_700 = 'FF8F2433';
    const BRAND_50 = 'FFFCE8E9';

    public function __construct($data, $filters = [])
    {
        $this->data = $data;
        $this->filters = $filters;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Proyectos Duales';
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'ESTADO',
            'INSTITUCIÓN',
            'EDO. INST.',
            'CIUDAD INST.',
            'ORGANIZACIÓN',
            'EDO. ORG.',
            'CIUDAD ORG.',
            'SECTOR',
            'TIPO ORG.',
            'NOMBRE PROYECTO',
            'ÁREA',
            'TIPO EDUCACIÓN',
            'CONVENIO',
            'ESTATUS PROYECTO',
            'CALIFICACIÓN',
            'ALUMNOS',
            'CERTIFICACIONES',
            'MICROCREDENCIALES',
            'DIPLOMAS',
            'BENEFICIOS',
        ];
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ID
            'B' => 12,  // ESTADO
            'C' => 25,  // INSTITUCIÓN
            'D' => 15,  // EDO. INST.
            'E' => 18,  // CIUDAD INST.
            'F' => 25,  // ORGANIZACIÓN
            'G' => 15,  // EDO. ORG.
            'H' => 18,  // CIUDAD ORG.
            'I' => 18,  // SECTOR
            'J' => 18,  // TIPO ORG.
            'K' => 30,  // NOMBRE PROYECTO
            'L' => 20,  // ÁREA
            'M' => 20,  // TIPO EDUCACIÓN
            'N' => 15,  // CONVENIO
            'O' => 18,  // ESTATUS PROYECTO
            'P' => 15,  // CALIFICACIÓN
            'Q' => 30,  // ALUMNOS
            'R' => 25,  // CERTIFICACIONES
            'S' => 25,  // MICROCREDENCIALES
            'T' => 25,  // DIPLOMAS
            'U' => 25,  // BENEFICIOS
        ];
    }

    /**
     * @return array
     */
    public function array(): array
    {
        $exportData = [];

        foreach ($this->data as $index => $project) {
            $students = '';
            if (isset($project['raw_students']) && is_array($project['raw_students'])) {
                $studentNames = array_map(function($student) {
                    return $student['name'] ?? '';
                }, $project['raw_students']);
                $students = implode(', ', array_filter($studentNames));
            } elseif (isset($project['student_name'])) {
                $students = $project['student_name'];
            }

            $certifications = '';
            if (isset($project['certifications']) && is_array($project['certifications'])) {
                $certNames = array_map(function($cert) {
                    return $cert['name'] ?? '';
                }, $project['certifications']);
                $certifications = implode(', ', array_filter($certNames));
            }

            $microcredentials = '';
            if (isset($project['microcredentials']) && is_array($project['microcredentials'])) {
                $microNames = array_map(function($micro) {
                    return $micro['name'] ?? '';
                }, $project['microcredentials']);
                $microcredentials = implode(', ', array_filter($microNames));
            }

            $diplomas = '';
            if (isset($project['diplomas']) && is_array($project['diplomas'])) {
                $diplomaNames = array_map(function($diploma) {
                    return $diploma['name'] ?? '';
                }, $project['diplomas']);
                $diplomas = implode(', ', array_filter($diplomaNames));
            }

            $benefitTypes = '';
            if (isset($project['benefit_types']) && is_array($project['benefit_types'])) {
                $benefitNames = array_map(function($benefit) {
                    $quantity = $benefit['quantity'] ?? 1;
                    return $benefit['name'] . ($quantity > 1 ? " (x{$quantity})" : '');
                }, $project['benefit_types']);
                $benefitTypes = implode(', ', array_filter($benefitNames));
            }

            $exportData[] = [
                $project['id'] ?? 'N/A',
                $project['has_report'] == 1 ? 'COMPLETADO' : 'INCOMPLETO',
                $project['institution_name'] ?? 'Por definir',
                $project['institution_state'] ?? 'Por definir',
                $project['institution_city'] ?? 'Por definir',
                $project['organization_name'] ?? 'Por definir',
                $project['organization_state'] ?? 'Por definir',
                $project['organization_city'] ?? 'Por definir',
                $project['organization_sector'] ?? 'Por definir',
                $project['organization_type'] ?? 'Por definir',
                $project['project_name'] ?? 'Por definir',
                $project['area'] ?? 'Por definir',
                $project['education_type'] ?? 'Por definir',
                $project['agreement'] ?? 'Por definir',
                $project['project_status'] ?? 'Por definir',
                $project['grade'] ?? 'N/A',
                $students,
                $certifications,
                $microcredentials,
                $diplomas,
                $benefitTypes,
            ];
        }

        return $exportData;
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                    'size' => 11,
                    'name' => 'Arial'
                ],
                'fill' => [
                    'fillType' => Fill::FILL_GRADIENT_LINEAR,
                    'startColor' => ['argb' => self::BRAND_800],
                    'endColor' => ['argb' => self::BRAND_900]
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FFCCCCCC']
                    ]
                ]
            ],

            'A1:U' . (count($this->data) + 1) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FFE0E0E0']
                    ]
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = count($this->data) + 1;
                $lastColumn = 'U';

                $headerRange = 'A1:' . $lastColumn . '1';
                $sheet->getStyle($headerRange)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['argb' => 'FFFFFFFF'],
                        'size' => 11,
                        'name' => 'Arial'
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_GRADIENT_LINEAR,
                        'startColor' => ['argb' => self::BRAND_800],
                        'endColor' => ['argb' => self::BRAND_900]
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ],
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => ['argb' => self::BRAND_700]
                        ]
                    ]
                ]);

                for ($row = 2; $row <= $lastRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle('A' . $row . ':' . $lastColumn . $row)
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setARGB(self::BRAND_50);
                    }
                }

                $sheet->getStyle('A2:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // ID
                $sheet->getStyle('B2:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // ESTADO
                $sheet->getStyle('P2:P' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // CALIFICACIÓN

                $textColumns = ['C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'Q', 'R', 'S', 'T', 'U'];
                foreach ($textColumns as $col) {
                    $sheet->getStyle($col . '2:' . $col . $lastRow)
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_LEFT)
                        ->setWrapText(true);
                }

                for ($row = 2; $row <= $lastRow; $row++) {
                    $estado = $sheet->getCell('B' . $row)->getValue();
                    $estatusProyecto = $sheet->getCell('O' . $row)->getValue();

                    if ($estado === 'COMPLETADO') {
                        $sheet->getStyle('B' . $row)
                            ->getFont()
                            ->getColor()
                            ->setARGB('FF2E7D32');
                    } elseif ($estado === 'INCOMPLETO') {
                        $sheet->getStyle('B' . $row)
                            ->getFont()
                            ->getColor()
                            ->setARGB('FFFF8F00');
                    }

                    if (strpos($estatusProyecto, 'Concluido') !== false) {
                        $sheet->getStyle('O' . $row)
                            ->getFont()
                            ->getColor()
                            ->setARGB('FF2E7D32');
                    } elseif (strpos($estatusProyecto, 'En progreso') !== false) {
                        $sheet->getStyle('O' . $row)
                            ->getFont()
                            ->getColor()
                            ->setARGB('FF1976D2');
                    } elseif (strpos($estatusProyecto, 'Pendiente') !== false) {
                        $sheet->getStyle('O' . $row)
                            ->getFont()
                            ->getColor()
                            ->setARGB('FFFF8F00');
                    }
                }

                $sheet->setAutoFilter('A1:' . $lastColumn . $lastRow);

                if (!empty($this->filters)) {
                    $filterText = "Filtros aplicados: ";
                    $filterDetails = [];
                    foreach ($this->filters as $key => $value) {
                        if (!empty($value)) {
                            $filterDetails[] = ucfirst(str_replace('_', ' ', $key)) . ": " . $value;
                        }
                    }
                    if (!empty($filterDetails)) {
                        $filterText .= implode(' | ', $filterDetails);

                        $infoRow = $lastRow + 2;
                        $sheet->setCellValue('A' . $infoRow, 'FILTROS APLICADOS:');
                        $sheet->setCellValue('B' . $infoRow, $filterText);

                        $sheet->getStyle('A' . $infoRow . ':B' . $infoRow)->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'color' => ['argb' => self::BRAND_800],
                                'size' => 10
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['argb' => 'FFF5F5F5']
                            ]
                        ]);

                        $sheet->mergeCells('B' . $infoRow . ':' . $lastColumn . $infoRow);
                    }
                }

                $dateRow = $lastRow + 4;
                $sheet->setCellValue('A' . $dateRow, 'Fecha de generación:');
                $sheet->setCellValue('B' . $dateRow, now()->format('d/m/Y H:i:s'));

                $sheet->getStyle('A' . $dateRow . ':B' . $dateRow)->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'size' => 9,
                        'color' => ['argb' => 'FF666666']
                    ]
                ]);

                $totalRow = $dateRow + 2;
                $sheet->setCellValue('A' . $totalRow, 'TOTAL REGISTROS:');
                $sheet->setCellValue('B' . $totalRow, count($this->data));

                $sheet->getStyle('A' . $totalRow . ':B' . $totalRow)->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 10,
                        'color' => ['argb' => self::BRAND_800]
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFFCE8E9']
                    ]
                ]);

                $sheet->freezePane('A2');

                $sheet->getPageSetup()->setHorizontalCentered(true);

                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);

                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);
            },
        ];
    }
}
