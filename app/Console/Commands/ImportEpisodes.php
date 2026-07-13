<?php

namespace App\Console\Commands;

use App\Models\Episode;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use ZipArchive;

class ImportEpisodes extends Command
{
    protected $signature = 'khamang:import-episodes {--source= : Absolute path to the files directory} {--fresh : Replace existing episodes}';
    protected $description = 'Import episodes 1-3 from 02_Episodes and 4-30 from 07_Web_Uploads.';

    public function handle(): int
    {
        $root = $this->option('source') ?: base_path('../files');
        if (!is_dir($root)) { $this->error("Source directory not found: {$root}"); return self::FAILURE; }
        if ($this->option('fresh')) Episode::query()->forceDelete();

        $files = [];
        foreach (glob($root.'/02_Episodes/ตอนที่ [123] *.docx') as $file) $files[$this->number($file)] = $file;
        foreach (glob($root.'/07_Web_Uploads/ตอนที่ *.docx') as $file) {
            if (str_contains(basename($file), 'ฉบับลงเว็บ')) $files[$this->number($file)] = $file;
        }
        ksort($files);
        foreach ($files as $number => $file) {
            if (!$number || $number > 30) continue;
            $title = preg_replace('/^ตอนที่\s*'.$number.'\s*/u', '', preg_replace('/_ฉบับลงเว็บ$/u', '', pathinfo($file, PATHINFO_FILENAME)));
            $content = $this->docxToHtml($file);
            Episode::updateOrCreate(['episode_number' => $number], [
                'title' => trim($title), 'slug' => $number.'-'.Str::slug($title, '-'),
                'excerpt' => Str::limit(trim(strip_tags($content)), 160), 'content' => $content,
                'cover_image_path' => 'images/KHW'.(($number - 1) % 6 + 1).'.png',
                'status' => 'published', 'published_at' => now(),
            ]);
            $this->line("Imported episode {$number}: {$title}");
        }
        return self::SUCCESS;
    }

    private function number(string $path): int { preg_match('/ตอนที่\s*(\d+)/u', basename($path), $m); return (int)($m[1] ?? 0); }
    private function docxToHtml(string $path): string
    {
        $zip = new ZipArchive; if ($zip->open($path) !== true) return '';
        $xml = $zip->getFromName('word/document.xml'); $zip->close();
        $doc = new \DOMDocument; $doc->loadXML($xml); $xp = new \DOMXPath($doc); $xp->registerNamespace('w','http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        $output = [];
        foreach ($xp->query('//w:body/w:p') as $paragraph) {
            $runs = [];
            foreach ($xp->query('.//w:r', $paragraph) as $run) {
                $text = ''; foreach ($xp->query('.//w:t', $run) as $node) $text .= $node->textContent;
                if ($text === '') continue;
                if ($xp->query('./w:rPr/w:b', $run)->length) $text = '<strong>'.e($text).'</strong>'; else $text = e($text);
                if ($xp->query('./w:rPr/w:i', $run)->length) $text = '<em>'.$text.'</em>';
                $runs[] = $text;
            }
            $line = trim(implode('', $runs)); if ($line !== '') $output[] = '<p>'.$line.'</p>';
        }
        return implode("\n", $output);
    }
}
