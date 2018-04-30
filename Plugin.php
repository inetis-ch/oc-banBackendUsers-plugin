<?php namespace Inetis\BanBackendUsers;

use Backend\Controllers\Users;
use Backend\Models\User;
use System\Classes\PluginBase;

/**
 * Ban Backend Users Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Ban Backend Users',
            'description' => 'Allow super users to ban other admins',
            'author'      => 'inetis Sàrl',
            'icon'        => 'icon-ban',
        ];
    }

    /**
     * Boot method, called right before the request route.
     */
    public function boot()
    {
        User::extend(function (User $model) {
            $model->implement[] = 'Inetis\Banbackendusers\UserBehavior';
        });

        $this->addIsBannedColumn();
        $this->addIsBannedField();
    }

    private function addIsBannedColumn()
    {
        Users::extendListColumns(function ($list, $model) {

            if (!$model instanceof User) {
                return;
            }

            $list->addColumns([
                'inetis_is_banned' => [
                    'label' => 'Banned',
                    'type'  => 'switch',
                ],
            ]);
        });
    }

    private function addIsBannedField()
    {
        Users::extendFormFields(function (\Backend\Widgets\Form $form, \Model $model, $context) {

            if (!$model instanceof User || $form->isNested) {
                return;
            }

            $form->addFields([
                'inetis_is_banned' => [
                    'label'   => 'Banned',
                    'comment' => 'Banned user can\'t login',
                    'type'    => 'switch',
                ],
            ]);

        });
    }
}
