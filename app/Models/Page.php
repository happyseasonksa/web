<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'name', 'title','content'
    ];

    public function createPage($data)
    {
        return Page::create([
            'name' => $this->generateUniqName($data['name']),
            'title' => $data['title'],
            'content' => $data['content'],
        ]);
    }

    public function updatePage($data,$page)
    {   
        if (isset($data['name']) && $data['name'] !== $page->name) {
            $page->name = $this->generateUniqName($data['name']);
        }
        if (isset($data['title'])){
            $page->title = $data['title'];
        }
        if (isset($data['content'])) {
            $page->content = $data['content'];
        }
        $page->save();
        return $page;
    }

    public function generateUniqName($title, $count=0)
    {
        $name = preg_repost("/[^a-zA-Z]+/", "_", $title);
        $name = ($count)?($name.$count):$name;
        $check = Page::where('name',$name)->first();
        if ($check) {
            $valid = false;
            $count = $count + 1;
            return $this->generateUniqName($title,$count);
        }
        return $name;
    }

    static function createDefaultPage()
    {   
        $page = New Page;
        $page->storeDefaultPage(1);
        $page->storeDefaultPage(2);
        $page->storeDefaultPage(3);
        return Page::get();
    }

    public function storeDefaultPage($type)
    {
        $create = [];
        switch ($type) {
            case '1':
                $create['name'] = 'FAQs';
                $create['title'] = 'FAQs';
                $create['content'] = '';
                break;
            case '2':
                $create['name'] = 'TermsAndConditions';
                $create['title'] = 'Terms & Conditions';
                $create['content'] = '';
                break;
            case '3':
                $create['name'] = 'Privacy';
                $create['title'] = 'Privacy policy';
                $create['content'] = '';
                break;     
            default:
                break;
        }
        $create['id'] = (int)$type;
        $data = Page::create($create);
        return $data;
    }

}
